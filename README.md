Php Azure quick uploads
=======================

- type in terminal: composer install
- in index.php set your params: $accountName and $accountKey



1) create container
```php
$azure = new Blob('your-container-name');
$azure->createCont();
```

2) upload blob in the new or existing container
```php
$azure = new Blob('your-container-name');
$azure->createBlob('path/to/file.ext');
```

3) list all blobs
```php
$azure = new Blob('your-container-name');
$azure->listBlobs();
```
