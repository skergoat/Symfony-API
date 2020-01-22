# symfony-api
First attempt to make a little test API on Symfony

- LOAD APP : 
   
   Download 
      
      $ git clone  
      
   Dependencies 
      
      $ composer install 
   
   Database 
   
      $ php bin/console doctrine:database:create
      $ php bin/console make:migration
      $ php bin/console doctrine:migrations:migrate      
      $ php bin/console doctrine:fixtures:load
      
   Run 
  
      $ symfony server:start 
   
   go to http://127.0.0.1:8000/ 



- GENERATE OPENSSL 

      $ mkdir -p config/jwt
      $ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
      $ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
      
   Go to 

      config > jwt > private.pem
      config > jwt > public.pem

   And copy keys 

   Go to src > security > token.html.twig and paste public and private keys in the template at their respective places   


- USE API  

   From homepage go to "Sign In" page and authenticate 
   
      username: user
      password: 000

    From Dashboard go to "Create access token"
      
      Click on "Get Token"
      Copy token 
      Go to https://jwt.io/ to activate the token 
      On the webpage paste public, private keys and token 
   
   Then go to API Documentation. Click on "Authorize" and paste token. In the field write "bearer" just before the token like    this : 
   
      bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9......

   Register token and then use API documentation  
      
      

