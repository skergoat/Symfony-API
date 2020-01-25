# symfony-api
First attempt to make a little test API on Symfony

# LOAD APP : 
   
   1. Download 
      
            $ git clone  
      
   2. Dependencies 
      
            $ composer install 
   
   3. Database 
   
            $ php bin/console doctrine:database:create
            $ php bin/console make:migration
            $ php bin/console doctrine:migrations:migrate      
            $ php bin/console doctrine:fixtures:load
      
   4. Run 
  
            $ symfony server:start 
   
   go to http://127.0.0.1:8000/ 



# GENERATE OPENSSL 

      $ mkdir -p config/jwt
      $ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
      $ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
   
      *** Phrase = phrase *** 
      
   1. Go to 

     config > jwt > private.pem
     config > jwt > public.pem

   2. And copy keys 

   3. Go to src > security > token.html.twig and paste public and private keys in the template at their respective places   


# USE API  

   1. From homepage go to "Sign In" page and authenticate 
   
     username: user
     password: 000

    From Dashboard go to "Create access token"
      
      Click on "Get Token"
      Copy token 
      Go to https://jwt.io/ to activate the token 
      On the webpage paste public, private keys and token 
   
   2. Then go to "API Documentation". Click on "Authorize" and paste token. In the field write "bearer" just before the token        like this : 
   
     bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9......

   3. Register token and then use API documentation  
   
   All functionalities does not work. It's not a real API, just a test 
      

