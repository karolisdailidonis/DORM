# Jobs
Currently, each request is basically successful and for each job is only apparent in the "Error" object. It is therefore up to the user whether the request was successful, e.g. if only 4/5 were successful.

```json
"errors": [ // each job has its own error message as object, 
	{
		"message": "<Error message>",
		"request": {} // Your DORM request for this query
	}
]
```

A strict module is in planning
## read
```json
"body": {
	"<name-of-requested-table": {
		"rows": [{}, {}], // Requested data as Objects
		"references": [],
		"query": "<Built SQL as string from the request>"   
	}
} 
```

## insert
```json
"body": {
	"<name-of-requested-table": {
		"result": { "insertID": "<ID>" }, // the row ID from new insert
		"query": "<Built SQL as string from the request>"
	}
},
```
## delete
```json
"body": {}, // empty body
```
## update
```json
"body": {
	"<name-of-requested-table": {
		"query": "<Built SQL as string from the request>"
	}
}, // empty body
```
