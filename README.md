# behat-salesforce

A example on how to connect to salesforce through rest api
and complete end to end scenario using Behat in a BDD way.
Just to reduce lengthy E2E tests.

# pre-requisites

To authenticate with Salesforce, you need to define it as a new connected app within the
Salesforce organization that informs Salesforce of this new authentication entry point.
more on how to
https://developer.salesforce.com/docs/atlas.en-us.api_rest.meta/api_rest/intro_defining_remote_access_applications.htm

User/Test must know authentication methods used for salesforce.
Username-password authentication is used for this example purpose.
This need below parameters for while requesting authentication
  - grant_type : password.
  - client_id: The Consumer Key from the connected app definition.
  - client_secret: The Consumer Secret from the connected app definition.
  - username: End-user’s username.
  - password: passwordXXXXXXXXXX. security token must be appended.
https://developer.salesforce.com/docs/atlas.en-us.api_rest.meta/api_rest/intro_understanding_username_password_oauth_flow.htm


# Install:

git clone https://github.com/nirbhaypatil/behat-salesforce.git
OR
download zip folder

Use composer install command to download required libs.

# How to use:

Next use SalesforceContext.php in yml and necessary step definition can be written.

Use the Query resource to execute a SOQL query that returns all the results in a single response.

 $query = 'SELECT Id,Name FROM ACCOUNT LIMIT 100';



