# behat-salesforce

A example on how to connect to salesforce through rest api
and complete end to end scenario using Behat in a BDD way.
Just to reduce lengthy E2E tests.

#pre-requisites

User/Test must know authentication methods used for salesforce.
Username-password authentication is used for this example purpose.
This need below parameters for while requesting authentication
  - grant_type : password.
  - client_id: The Consumer Key from the connected app definition.
  - client_secret: The Consumer Secret from the connected app definition.
  - username: End-user’s username.
  - password: passwordXXXXXXXXXX. security token must be appended.
https://developer.salesforce.com/docs/atlas.en-us.api_rest.meta/api_rest/intro_understanding_username_password_oauth_flow.htm


#install

1.git clone https://github.com/nirbhaypatil/behat-salesforce.git
OR
2.download zip folder

Use composer install command to download all libs in vendor



