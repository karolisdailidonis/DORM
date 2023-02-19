Simple:
```json
"where": [
	{
		"column": "<column_name>",
		"value": "<value>", 
		"condition": "="
	}
]
```
Extended:
```json
"where": [
	{
		"op": "AND / OR",
		"column": "<column_name>",
		"val1": "<value>", 
		"val2": "<value>", 
		"condition": "BETWEEN"
	}
]
```