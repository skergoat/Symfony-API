# symfony-api
First attempt to make a little test API on Symfony

Load API : 
   
   Download 
      
      - git clone  
      
   Dependencies 
      
      - composer install 
   
   Database 
   
      - php bin/console doctrine:database:create
      - php bin/console make:migration
      - php bin/console doctrine:migrations:migrate
  
  When migrating if there is an exception about user table, try first to finish installation :  
      
      - php bin/console doctrine:fixtures:load
      
  Run 
  
      - symfony server:start 
      - go to http://127.0.0.1:8000/ 


Use API

From homepage, go "Sign In" button and authenticate 

      - username: user
      - password: 000

From Dashboard go to "Create access token"
      
      - click on "Get Token"
      - 
