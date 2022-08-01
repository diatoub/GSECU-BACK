<?php
namespace App\Listener;

use App\Entity\Config;
use App\Entity\Parametre;
use App\Entity\Useragence;
use DateTime;
use App\Entity\User;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Ldap\Exception\LdapException;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Ldap\Exception\ConnectionException;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Monolog\Logger;
use Psr\Container\ContainerInterface;

class AuthenticationFailureListener
{
	/**
	 * @param \Doctrine\ORM\EntityManager $em
     * @param RequestStack $requestStack
     * @param JWTEncoderInterface  $JWTEncoder
     * @param UserPasswordEncoderInterface $passwordEncoder
	 * @param ContainerInterface $container
	 */
    
    /**
     * @var integer
     */
    protected $duration;
	public function __construct($em,ContainerInterface $container, RequestStack $requestStack,JWTEncoderInterface  $JWTEncoder,UserPasswordEncoderInterface $passwordEncoder) {
        $this->em = $em;
        $this->requestStack = $requestStack;
        $this->JWTEncoder=$JWTEncoder;
        $this->passwordEncoder=$passwordEncoder;
		$this->container=$container;
	}
	
	/**
	 * @param AuthenticationFailureEvent $event
	 */
    
	public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event)
	{
     //   $this->container-('mailer.transport', 'babs');
       // dd($this->container->getParameter('base_dn'));
        //$containerBuilder=new ContainerBuilder();
        //    $containerBuilder->setParameter('base_dn','babs');
        //dd($this->container-> getParameter('base_dn'));
        $this->duration = microtime(true);
		$message="Authentification";
        $this->container->get('monolog.logger.trace')->log(Logger::INFO, $message, array('container' => $this->container, 'event' => 'REQUEST'));
        $request= $this->requestStack->getCurrentRequest();
        $post=json_decode($request->getContent(),true);
        $username=$post["username"];
        $password=$post["password"];
        $user=$this->em->getRepository(User::class)->findOneByUsername($username);
        $requestedPassword=$user?$user->getPasswordRequestedAt():null;
        $now=new DateTime(date("Y-m-d"));
        $interval=$requestedPassword?($now->diff($requestedPassword))->format('%a'):0;
        $now=strtotime(date("Y-m-d H:i:s"));
        if(!$user){
            $result =array("code"=>503,"status"=>false,"message"=>"Nom d'utilisateur invalide");
            $event->setResponse(new JsonResponse($result));
        }
        else{
            $login=0;
          //  $login=$user->getLoginTentative()?$user->getLoginTentative():0;
         //   $last_login=$user->getLoginTentativeAt()?strtotime(($user->getLoginTentativeAt())->format("Y-m-d H:i:s")):null;
          /*   if($interval > 90){
                $response= new JsonResponse(array("code"=>504,"status"=>false,"message"=>"votre mot de pass a expiré, veuillez le changer"));
            }
            else */
            $last_login=0;
            if($login >= 3 && (($now - $last_login)/60) <= 1){
                $user->setEnabled(0);
              //  $user->setLoginTentativeAt(new DateTime(date("Y-m-d H:i:s")));
                $this->em->persist($user);
                $result =array("code"=>503,"status"=>false,"message"=>"votre compte est bloqué suite à trois tentatives de connexions sans succés");
            }else if($login >= 3 && (($now - $last_login)/60) >=2){
                $user->setEnabled(1);
               // $user->setLoginTentative(0);
                $this->em->persist($user);
                $result= $this->login($username,$password,$post,$login);
            }else{
                $user->setEnabled(1);
              //  $user->setLoginTentativeAt(new DateTime(date("Y-m-d H:i:s")));
                $result=$this->loginLdap($post,$user,$login);
            }
            $this->em->flush();
            $this->duration = (microtime(true) - $this->duration)*1000;
            $this->container->get('monolog.logger.trace')->log(Logger::INFO, $message, array(
                'container' => $this->container, 'event' => 'RESPONSE', 'response' => $event->getResponse(), 'duration' => $this->duration
            ));
        $event->setResponse(new JsonResponse($result));
        }
    }
    
    public function login($username,$password,$post,$login)
    {
        $repo = $this->em->getRepository(User::class);
        $user = $repo-> findOneBy(['username' => $username]);
        if(!$user){
            return [
                'code' => 'ko',
                'status'=>false,
                'message' => 'Username incorrect'
            ];
        }
        $isValid = $this->passwordEncoder->isPasswordValid($user, $password);
            if($isValid){
                $token = $this->JWTEncoder->encode([
                    'username' => $user->getUsername(),
                    'roles'=>$user->getRoles(),
                    'exp' => time() + 86400 // 1 day expiration
                ]);
                $data = array(
                    'id'			=> $user->getId(),
                    'username' 		=> $user->getUsername(),
                    'email'  		=> $user->getEmail(),
                    'role'    		=> $user->getRoles(),
                    'nomComplet'    => $user->getNomComplet(),
                    'telephone'  	=> $user->getTelephone(),
                    'profil'		=>( $user->getProfil())?$user->getProfil()->getLibelle():null,
            );
                return [
                    'token' => $token,
                    'data'=>$data
                ];
        }
        else {
            return $this->loginLdap($post,$user,$login);
        }
    }

    public function loginLdap($post,$user,$login){
        $societeId=$user?$user->getStructure()? $user->getStructure()->getSociete()? $user->getStructure()->getSociete()->getId():null:null:null;

        if ($societeId){
            $port_ldap =$this->em->getRepository(Parametre::class)->getParam(Config::PORT_LDAP,$societeId) ;
            $host_ldap = $this->em->getRepository(Parametre::class)->getParam(Config::HOST_LDAP,$societeId) ;
            $query_ldap = $this->em->getRepository(Parametre::class)->getParam(Config::QUERY_LDAP,$societeId) ;
            $dn_ldap = $this->em->getRepository(Parametre::class)->getParam(Config::DN_LDAP,$societeId) ;
            $mail_ldap = $this->em->getRepository(Parametre::class)->getParam(Config::MAIL_LDAP,$societeId) ;
            if ($port_ldap && $host_ldap && $query_ldap &&$dn_ldap){
                try {
                $ldap = Ldap::create('ext_ldap', ['host' => $host_ldap->getValeur(), 'port' => $port_ldap->getValeur(), 'encryption' => 'none', 'version' => 3, 'referrals' => false]);

                    $ldap->bind(sprintf('%s'.$mail_ldap->getValeur(), $post['username']), $post['password']);
                    $query = $ldap->query($dn_ldap->getValeur(), '(&('.$query_ldap->getValeur().'='.$post['username'].'))');
                    $result = $query->execute()->toArray();
                    $value=null;
                    foreach($result as $entry) {
                        if($entry->getAttributes()) {
                            $value = $entry->getAttributes();
                            break;
                        }
                    }
                    if(!$user){
                        $result= array('status' => "false",'code'=>502,'message'=>'utilisateur inexistant');
                    }
                    else{
                        $token = $this->JWTEncoder->encode([
                            'username' => $value['sAMAccountName'][0],
                            'roles'=>$user->getRoles(),
                            'exp' => time() + 3600 // 1 hour expiration
                        ]);
                         $result = array('token' => $token,'status' => true, 'data' => array(
                            'prenom' => $value['givenName'][0], 'nom' => isset($value['sn'])?$value['sn'][0]:'', 'email' => $value['mail'][0], 'username' => $value['sAMAccountName'][0],
                            'matricule' => isset($value['initials'][0]) ? $value['initials'][0] : null,
                            'telephone' => isset($value['telephoneNumber'][0]) ? $value['telephoneNumber'][0] : null,
                            'roles'=>$user->getRoles(),
                            'profil'		=>($user->getProfil())?$user->getProfil()->getLibelle():null,

                        ));
                    }

                } catch(LdapException $e) {
                    //   var_dump($e->getMessage());
                    $login++;
                    //  $user->setLoginTentative($login);
                    $this->em->persist($user);
                    $result = array('status' => "false",'code'=>502,'message'=>'login ou mot de passe incorrect');
                }
            }else{
                $result = array('status' => "false",'code'=>502,'message'=>"Impossible de s'authentifier avec cet utilisateur");
            }

        }else{
            $result = array('status' => "false",'code'=>502,'message'=>"Impossible de s'authentifier avec cet utilisateur");
        }
        
        $verifPass = $this->passwordEncoder->isPasswordValid($user, $post['password']);
        if (!$verifPass){
            $result = array("code"=>503,"status"=>false,"message"=>"Mot de passe incorrecte");
        }

        return $result;
    }
}
