#API Test

- A fraction of an hour is considered 1 hour (Example: 8:01 AM is considered 9:00 AM).

## Create employee record

Creates an employee record.

### Request

`POST /api/employee/create`

Request Body

    first_name      required    First name of employee
    last_name       required    Last name of employee
    middle_name                 Middle name of employee
    email           required    Email address of employee
    contact_no                  Contact number of employee

Example Request

    curl --request POST \
    --url 'http://localhost:8080/api/employee/create' \
    --header 'Content-Type: application/json' \
    --data '{ \
        "first_name": "Jared", \
        "last_name": "Wharton", \
        "last_name": "Hall", \
        "email": "JaredMWharton@rhyta.com" \
        "contact_no": "630-535-9823" \
    }'


### Responses

    204     Employee record created successfully
    400     Possible error: see response message
    500     Service unavailable

## Create DTR

Creates a daily time record.

### Request

`POST /api/dtr/create`

Request Body

    email           required    Email address of employee
    date            required    Date of time record
    time_in         required    Time in
    time_out        required    Time out

Example Request

    curl --request POST \
    --url 'http://localhost:8080/api/dtr/create' \
    --header 'Content-Type: application/json' \
    --data '[ \
        { \
            "email":"JaredMWharton@rhyta.com", \
            "date":"2022-09-07", \
            "time_in":"7:45 AM", \
            "time_out":"3:00 PM" \
        }, \
        { \
            "email":"JaredMWharton@rhyta.com", \
            "date":"2022-09-08", \
            "time_in":"8:00 AM", \
            "time_out":"6:02 PM" \
        }, \
        { \
            "email":"StellaACornish@jourrapide.com", \
            "date":"2022-09-05", \
            "time_in":"7:45 AM", \
            "time_out":"5:05 PM" \
        }, \
        { \
            "email":"StellaACornish@jourrapide.com", \
            "date":"2022-09-06", \
            "time_in":"8:05 AM", \
            "time_out":"5:25 PM" \
        } \
    ]'


### Responses

    204     DTR record created successfully
    400     Possible error: see response message
    500     Service unavailable

## Fetch DTR record

Returns a JSON formatted array of employee logs.

### Request

`GET /api/dtr/log`

Request Body

    email           required    Email address of employee

Example Request

    curl --request GET \
    --url 'http://localhost:8080/api/dtr/log?email=ViolaRSanderson@dayrep.com'


### Responses

    200     OK
    400     Possible error: see response message
    500     Service unavailable
    
Example Response

    [
        [
            "Hiner, Kathleen",
            "2022-09-05",
            "7:45 AM",
            "5:05 PM",
            8,
            0,
            0,
            1
        ],
        [
            "Hiner, Kathleen",
            "2022-09-06",
            "8:05 AM",
            "5:25 PM",
            8,
            1,
            0,
            1
        ]
    ]
    
## Show DTR record

Displays an HTML formatted employee DTR records.

### Request

`GET /dtr/displayLog`

Request Body

    email           required    Email address of employee

Example Request

    curl --request GET \
    --url 'http://localhost:8080/dtr/displayLog?email=ViolaRSanderson@dayrep.com'


### Responses

    200     OK
    400     Possible error: see response message
    500     Service unavailable
    
Example Response

    Name	        Date	    Time In     Time Out    Hrs Worked  Hrs Late    Hrs Undertime   Hrs Overtime
    Decker, Samuel	2022-09-05  7:45 AM     5:05 PM     8           0           0               1
    Decker, Samuel	2022-09-06  8:05 AM     5:25 PM     8           1           0               1
    Decker, Samuel	2022-09-07  7:45 AM     3:00 PM     6           0           2               0
    Decker, Samuel	2022-09-08  8:00 AM     6:02 PM     9           0           0               2  
