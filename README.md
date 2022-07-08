# APIMatic_Accessment
Inde.php file cotains all the code, which was required for this assessment. 
<h2>Queries Examples<h2>
<h4>1. To Get Data About Specific Country use Query: /data/{Country Name}</h4>
<h4>2. To Get All Data Uue Query: /data or /data/</h4>
<h4>3. To Get Sorted Data: /data/sor={Field Name}</h4>
<h5 style='color: red'>Note, In single Query use only one Field/Attribute to sort data multiple attributes in single query is not supported</h5>
<h4>4. To Get Data according to set of attributes use Query: /data?{Attribute Name}[lte OR gte: this is optional]
<h5 style='color: red'>OR</h5>
<h4>5. /data?{Attribute Name}[lte OR gte: this is optional]&{Attribute Name}[lte OR gte: this is optional]....</h4>
<h5 style='color: red'>Note: Data According to Country Can't Be Fetched According to this Rule. Use Query 1 To Get Specific Data According to Country</h5>
