```json
{
	"job": "update",
	"values": {
		"<column_name>": "<value>",
		"<column_name>": "<value>",
	},
	"from": "<table_name>",
	"where": [
		{
			"column": "<column_name>",
			"value": "<value>", 
			"condition": "="
		},
		{
			"op": "AND / OR",
			"column": "<column_name>",
			"val1": "<value>", 
			"val2": "<value>", 
			"condition": "BETWEEN"
		}
	]
},
```