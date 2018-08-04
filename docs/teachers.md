Students API
------------

#Authentication method

To authenticate a **X-AUTH-TOKEN** header must be passed

#Fetch all teachers
```
method: GET
endpoint: /teachers
```
#Fetch specific teacher
```
method: GET
endpoint: /teacher/id
```
#Example response
```
[
    {
        "id": 6,
        "firstName": "Professor 1",
        "lastName": "Example 1",
        "email": "professor@example.com",
        "room": "123"
    },
    {
        "id": 7,
        "firstName": "Professor 1",
        "lastName": "Example 1",
        "email": "professor@example.com",
        "room": "123"
    }
]
```