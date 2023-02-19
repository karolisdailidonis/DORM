```json
{
	"job": "read",
	"columns": [
		{"column": "<column_name>"},
		{"column": "<column_name>"}
	],
	"from": "<table_name>",
	"join":[
	{ 
		"<table_name>": "<column_name>",
		"<table_name>": "<column_name>"
	}
	],
	"order": {
		"column": "<column_name>",
		"sort": "DESC"
	},
	"limit": 1000, 
	"embed": [
		{"table": "<table_name>"}
	]
},
```