# Rest API Sanctum

***This project is a base structure of Laravel with a user manager, roles and permissions.***
1. Laravel 9 API RESTFULL with latest PHP version.

# Instalation steps
1. Install all composer dependencies `composer install` or `composer i`
2. Copy and paste `.env.example`, then replace the name of the copy for `.env`
3. Run `php artisan key:generate` to make `APP_KEY` enviroment value to run the laravel app
4. Create a database with any name
5. Update the `DB_HOST`, `DB_DATABASE`, `DB_USERNAME` and `DB_PASSWORD` for the right access to the database
6. Run `php artisan migrate:fresh --seed` to make the database and seed with fake data

> Note: For production databases, generate a sql backup, import for local and then run the command `php artisan migrate`.
> This will regenerate the migrations to latest version without down the production database information.

# Common HTTP Status Codes for a REST API (convenion)
***2xx as Success***\
2xx-range codes are the codes which determine that the processing of a request has been successful.
- `200 OK` - The status code indicates that the request has been successful, and the response contains a result. This status code is one of the most common codes. This code can be used for the HTTP methods GET, PUT and DELETE.
- `201 CREATED` - The status code indicates that the request has been completed, and a new resource has been created successfully. This code can be used for the HTTP methods PUT and POST.
- `204 NO CONTENT` - The status codes signify that a request has been successfully received and processed, but there are no results to return.

***4xx as Client Error***\
4xx-range codes are the status codes that deal with client errors. For instance, a client has not been authenticated for an operation or the request has been sent to an endpoint that does not exist.
- `400 BAD REQUEST` - The status code indicates the fact that a server hasn’t been able to process the request a client has sent, due to invalid syntax.
- `401 UNAUTHORIZED` - The status code shows that the access is denied, and the authentication-related information (e.g. username, password or token) hasn’t been sent through the request.
- `403 FORBIDDEN` - The status code indicates that a client has tried to access a resource which it does not have the permission to access to. This might occur when a user does not have the enough privileges to access a specific resource.
- `404 NOT FOUND` - The status code indicate that the client has sent a request to a resource which could not be found. This code is one of the most common status codes which most of people, who are not even developers, have come across to, at least once in their lives.
- `405 METHOD NOT ALLOWED` - The status code is returned when a request has been send through a HTTP method that is not supported by the endpoint. For example, a request has been sent through the GET method to an endpoint which just supports the POST method. In this case, the 405 code will be returned to the client.
- `406 NOT ACCEPTABLE` - The status code means that format of the requested data in Accept header is not supported by server, and the server is not able to return the data to the requested format.
- `409 CONFLICT` - The status code indicates that the client is trying to create a duplicate record or perform a conflicting change which is not allowed.
- `413 REQUEST ENTITY TOO LARGE` - The status code indicates that the request was larger than the server is able to handle, either due to physical constraints or to settings. Usually, this occurs when a file is sent using the POST method from a form, and the file is larger than the maximum size allowed in the server settings.
- `415 UNSUPPORTED MEDIA TYPE` - The status code indicates that the determined format in the Content-Type header, could not be supported by server, and the server supports another format.
- `422 UNPROCESSABLE ENTITY` - The status code indicates that the server understands the content type of the request entity, and the syntax of the request entity is correct, but it was unable to process the contained instructions.
- `429 TOO MANY REQUEST` - The status code indicates the user has sent too many requests in a given amount of time ("rate limiting").

***5xx as Server Error***\
5xx-range codes are all, the status codes related to server errors. These codes mean that the server is informed about the occurred error or it might mean the server just hasn’t been able to process the request.
- `500 INTERNAL SERVER ERROR` - The status code represents an internal error in the server. This code can be used as a general status code, meaning that you can use this code whenever the error is caused by an unknown reason.

### For more info [click here](https://developer.mozilla.org/es/docs/Web/HTTP/Status)
