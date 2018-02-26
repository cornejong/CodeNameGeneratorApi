# CodeNameGeneratorApi
Simple CodeNameGenerator API

Returns a JSON array with a codename.

### Response
This is a basic response:

```JSON
{
    "status": true,
    "codename": "unnecessary mainframe",
    "uid": "5a94869281c34",
    "timestamp": 1519683218
}
```

## Response Restrictions
You can also specify the atribute and object group.
Only values in those groups will be used for the creation of the codename. 

You provide these response restrictions via 'get' parameters:

#### e.g.
```
127.0.0.1:8080/your/dir/index.php?atribute=volitility&object=sealife
```

### ```Status = false```
If response status returns false. Check the message for more info.

#### e.g.
```JSON
{
    "status": false,
    "message": "The given atribute group does not exist.",
    "uid": "5a948410e8067",
    "timestamp": 1519682576
}
```

