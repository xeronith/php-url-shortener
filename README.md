# PHP Url-Shortener

This sample demonstrates a loosely-coupled extensible architecture with a working implementation of a shortener service. This implementation supports SQLite and MySQL storages. Everything has been implemented from scratch with no dependency to external libraries. The main focus was to create a robust architecture which can be scaled and enhanced upon indefinitely, hence many trivial and minor details, extensive logs, numerous tests and improvements were omitted for the sake of brevity and faster delivery.

API Example:

Create:
`curl -X POST -H 'Authorization: Bearer VALID_API_KEY_1' http://localhost:8080/api/url/ -d '{"url": "https://www.php.net"}'`

Redirect:
`curl -v http://localhost:8080/Ghq`

Update:
`curl -X PUT -H 'Authorization: Bearer VALID_API_KEY_1' http://localhost:8080/api/url/ -d '{"key": "Ghq", "url": "https://www.php.net"}'`

Remove:
`curl -X DELETE -H 'Authorization: Bearer VALID_API_KEY_1' http://localhost:8080/api/url/ -d '{"key": "Ghq"}'`
