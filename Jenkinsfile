pipeline {
    /* choisir un slave Jenkins qui a le label php7 */
    agent  {
        label 'dst-preprod'
    }
    environment {
        EMAIL_RECIPIENTS = 'MoctarThiam.MBODJ@orange-sonatel.com, Madiagne.Sylla@orange-sonatel.com, Mohamed.SALL@orange-sonatel.com, MelchisedeckFolloh.MABIALA@orange-sonatel.com'
        IMAGE = 'registry.tools.orange-sonatel.com/dd/qredic-backend'
        VERSION = readMavenPom().getVersion()
        NAME = readMavenPom().getArtifactId()
        APP_SECRET = '80c92ec886604ceafe60372b33c32d29'
        MAILER_URL="smtp://10.100.56.56:25"
        DATABASE_URL="mysql://gsecu:gsc_pma%40s2m@172.17.0.1:3306/gsecu_v2?serverVersion=13&charset=utf8"
        PROJECT_REC="dstgsecubackend-rec"
        DC="backend"
    }
    
    tools {
        maven "Maven_3.3.9"
    }

    stages {
        stage('Installation des packets') {
            steps {
                sh 'rm -rf composer.phar*'
                sh 'wget https://getcomposer.org/download/1.10.19/composer.phar'
                sh 'php74 -d memory_limit=-1 composer.phar install'
                sh 'php74 bin/console d:s:u --force'
                // sh 'php74 bin/console lexik:jwt:generate-keypair --skip-if-exists'
                sh 'sed -i "/DATABASE_URL/ s/^/# /" .env'
            }
        }

        stage(' Build Docker image') {
            steps {
                sh 'docker build  --no-cache -t ${IMAGE}:${VERSION} .'
                sh 'docker push ${IMAGE}:${VERSION}'
                script {
                    BUILD_CONFIG_REC = sh(
                            script: 'ruby -ryaml -rjson -e "puts JSON.pretty_generate(YAML.load(ARGF))" /var/openshift/qredic/rec/${DC}-deployment.yaml',
                            returnStdout: true
                        )
                }
            }
        }

        stage(' Deploy IN Dev') {
            when {
                anyOf { branch 'develop' }
            }
            steps {
                sh 'docker ps -qa -f name=${NAME} | xargs --no-run-if-empty docker rm -f'
                sh 'docker images -f reference=${IMAGE} -qa | xargs --no-run-if-empty docker rmi'
                sh 'docker run --name=${NAME} -d --restart=always -e DATABASE_URL=$DATABASE_URL -e MAILER_URL=$MAILER_URL -e APP_ENV=dev -e APP_DEBUG=0 -e APP_SECRET=$APP_SECRET --memory-reservation=256M --memory=512M -p 8081:80 -p 2281:22  ${IMAGE}:${VERSION}'
            }
        }
    }

    post {
        changed {
            emailext attachLog: true, body: '$DEFAULT_CONTENT', subject: '$DEFAULT_SUBJECT', to: '$EMAIL_RECIPIENTS'
        }
        failure {
            emailext attachLog: true, body: '$DEFAULT_CONTENT', subject: '$DEFAULT_SUBJECT', to: '$EMAIL_RECIPIENTS'
        }
    }
}

