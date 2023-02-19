```json
{
	"job": "insert",
	"values": {
		"<column_name>": "<value>",
		"<column_name>": "<value>",
	},
	"from": "<table_name>",
	"before": {
		"lastInsertId": { 
			"fromTable": "<table_name>", 
			"setColumn": "<column_name>" 
		},
	}
},
```