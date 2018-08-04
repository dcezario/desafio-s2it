Students API
------------

#Authentication method

To authenticate a **X-AUTH-TOKEN** header must be passed

#Fetch all students
```
method: GET
endpoint: /students
```
#Fetch specific student
```
method: GET
endpoint: /student/id
```
#Example response
```
[
    {
        "id": 1,
        "firstName": "Student",
        "lastName": "Example",
        "phone": "00 1111 2222",
        "email": "student@email.com"
    },
    {
        "id": 2,
        "firstName": "Student 2",
        "lastName": "Example 2",
        "phone": "00 1111 3223",
        "email": "student2@email.com"
    }
]
```