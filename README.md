<h1>DB_Stats</h1>

<h2>Introduction</h2>

<p><code>DB_Stats</code> is a PHP library designed to compute statistics on MySQL tables. It enables multi-dimensional analyses of a table (just like in OLAP although less powerfull).
When you want to analyse data in a table, you plug <code>DB_Stats</code> on that table. The table containing the data to analyse is called the "source table".
<code>DB_Stats</code> will generate a roll-up table (also called "destination table" or "stats table"), containing aggregated stats from the initial table. <code>DB_Stats</code> will also generate triggers
on the source table. Whenever data is inserted, updated or deleted from the source table, the triggers will automatically update statistics in the roll-up table.</p>

<p>Then, you can query the data in this table using the <a href="DB_Stats_Query.html"><code>DB_Stats_Query</code></a> class, that provides query capabilities on DB_Stats tables.</p>

<h2>Understanding DB_Stats</h2>

<p>In order to use <code>DB_Stats</code>, it is important to understand the way <code>DB_Stats</code> works.</p>
<p>Here is a sample source table. The table below contains persons from a medical record.</p>

<table border="1">
	<tr>
		<th>Id</th>
		<th>Name</th>
		<th>Job</th>
		<th>Country</th>
		<th>City</th>
		<th>CreationDate</th>
		<th>Age</th>
		<th>Weight</th>
		<th>Size</th>
	</tr>
	<tr>
		<td>1</td>
		<td>Alice</td>
		<td>Teacher</td>
		<td>France</td>
		<td>Paris</td>
		<td>2009-06-22 12:15:00</td>
		<td>25</td>
		<td>50</td>
		<td>170</td>
	</tr>
	<tr>
		<td>2</td>
		<td>Bob</td>
		<td>Lawyer</td>
		<td>France</td>
		<td>Marseille</td>
		<td>2009-07-01 05:05:05</td>
		<td>35</td>
		<td>75</td>
		<td>175</td>
	</tr>
	<tr>
		<td>3</td>
		<td>Charlie</td>
		<td>Teacher</td>
		<td>USA</td>
		<td>New-York</td>
		<td>2009-07-05 13:15:00</td>
		<td>55</td>
		<td>70</td>
		<td>180</td>
	</tr>
	<tr>
		<td>4</td>
		<td>Debbie</td>
		<td>IT Consultant</td>
		<td>USA</td>
		<td>San Francisco</td>
		<td>2009-08-02 18:00:00</td>
		<td>65</td>
		<td>65</td>
		<td>175</td>
	</tr>
	<tr>
		<td>5</td>
		<td>Eve</td>
		<td>IT Consultant</td>
		<td>USA</td>
		<td>San Francisco</td>
		<td>2009-08-02 19:00:00</td>
		<td>30</td>
		<td>55</td>
		<td>185</td>
	</tr>

</table>

<p>There are a few columns to describe these patients:</p>
<ul>
	<li>The name</li>
	<li>The job</li>
	<li>The location (country/city)</li>
	<li>The date the user was added to the database</li>
	<li>A set of data about the person (age / weight (in kg) / size (in cm))</li>
</ul>

<p>We will split those columns in 2 sets: <em>dimensions</em> and <em>values</em>. Dimensions are the "things" we will filter upon. Values are the "things" we will sum up to make stats.</p>
<p>In this sample, we will choose those dimensions:</p>

<ul>
	<li>Job</li>
	<li>Location (Country / City)</li>
	<li>Creation date (Year / Month / Day)</li>
</ul>
<p>As you noticed, a <em>dimension</em> can contain several columns (in this sample, the location is made of 2 columns and the creation date is made of 3 columns).</p>
<p>The values will be:</p>
<ul>
	<li>Sum of Age</li>
	<li>Sum of Weight</li>
	<li>Sum of Size</li>
	<li>Number of patients</li>
</ul>

<p>Computing the average:</p>
<p>Values are numbers that are summed from the original dataset. In this sample, we might want the average value for the age of the patients in the database. However, <code>DB_Stats</code>
does only provide us with the sum of the age. Hopefully, we can also compute the number of patients (by adding 1 to a stats row each time a row is inserted). Therefore,
we can compute the average by dividing the sum of the ages by the number of patients.</p>

<p>With those dimensions and those values, the roll-up table will certainly look like this:</p>

<table border="1">
	<tr>
		<th>Id</th>
		<th>Job</th>
		<th>Country</th>
		<th>City</th>
		<th>Year</th>
		<th>Month</th>
		<th>Day</th>
		<th>SumAge</th>
		<th>SumWeight</th>
		<th>SumSize</th>
		<th>Cnt</th>
	</tr>
	<tr>
		<td>1</td>
		<td>Teacher</td>
		<td>France</td>
		<td>Paris</td>
		<td>2009</td>
		<td>06</td>
		<td>22</td>
		<td>25</td>
		<td>50</td>
		<td>170</td>
		<td>1</td>
	</tr>
	<tr>
		<td>2</td>
		<td>Lawyer</td>
		<td>France</td>
		<td>Marseille</td>
		<td>2009</td>
		<td>07</td>
		<td>01</td>
		<td>35</td>
		<td>75</td>
		<td>175</td>
		<td>1</td>
	</tr>
	<tr>
		<td>3</td>
		<td>Teacher</td>
		<td>USA</td>
		<td>New-York</td>
		<td>2009</td>
		<td>07</td>
		<td>05</td>
		<td>55</td>
		<td>70</td>
		<td>180</td>
		<td>1</td>
	</tr>
	<tr>
		<td>4</td>
		<td>IT Consultant</td>
		<td>USA</td>
		<td>San Francisco</td>
		<td>2009</td>
		<td>08</td>
		<td>02</td>
		<td>95</td>
		<td>120</td>
		<td>360</td>
		<td>2</td>
	</tr>
</table>

<p>In this sample, the data has been grouped by all the columns. Since there are 2 patients that are both IT Consultant, live in the USA at San Francisco and where created in 2009-08-02, their data is summed.</p>
<p>Of course, this can seem pretty useless, since a simple "GROUP BY" request does exactly the same think. But keep in mind that:</p>
<ul>
	<li>this data is updated in real time, so the source table can contain millions of records and you can still get instant resuls</li>
	<li><code>DB_Stats</code> provide much more, as we will see below</li>
</ul>

<p>Indeed, <code>DB_Stats</code> does not only provide stats on one level of "GROUP BY", it will also perform all the possible GROUP BYs according to any relevant combination of
columns. So the roll-up table will also contain this:</p>

<table border="1">
	<tr>
		<th>Id</th>
		<th>Job</th>
		<th>Country</th>
		<th>City</th>
		<th>Year</th>
		<th>Month</th>
		<th>Day</th>
		<th>SumAge</th>
		<th>SumWeight</th>
		<th>SumSize</th>
		<th>Cnt</th>
	</tr>
	<tr>
		<td>5</td>
		<td>NULL</td>
		<td>NULL</td>
		<td>NULL</td>
		<td>NULL</td>
		<td>NULL</td>
		<td>NULL</td>
		<td>210</td>
		<td>365</td>
		<td>885</td>
		<td>5</td>
	</tr>
	<tr>
		<td>6</td>
		<td>Teacher</td>
		<td>NULL</td>
		<td>NULL</td>
		<td>NULL</td>
		<td>NULL</td>
		<td>NULL</td>
		<td>80</td>
		<td>120</td>
		<td>370</td>
		<td>2</td>
	</tr>
	<tr>
		<td>7</td>
		<td>Teacher</td>
		<td>France</td>
		<td>NULL</td>
		<td>NULL</td>
		<td>NULL</td>
		<td>NULL</td>
		<td>25</td>
		<td>500</td>
		<td>170</td>
		<td>1</td>
	</tr>
	<tr>
		<td>...</td>
		<td>...</td>
		<td>...</td>
		<td>...</td>
		<td>...</td>
		<td>...</td>
		<td>...</td>
		<td>...</td>
		<td>...</td>
		<td>...</td>
		<td>...</td>
	</tr>
</table>

<p>In order to read this, you must understand that when a column containes "NULL", it means the values contain are sumed for any possible values of the column.</p>
<p>So for instance, the row with ID "5" has NULL in all the columns related to the dimensions. Therefore, it will contain the sum of all the patients. So we can see instantly that we have 5 patients in database
and that the sum of there age is 210. Therefore, the average age is 42.</p>
<p>Row with ID "7" contains all the patients who are Teacher and who live in France, etc...</p>

<p><em>Important!</em><br/>
All the combinations of "NULL" columns are available according to there dimensions. <b>A dimension is made of an ordered list of columns.</b> This means that for the dimension YEAR/MONTH/DAY, we can 
search for stats regarding patients created in 2009, we can search for stats regarding patients created in June 2009, but we CANNOT search for stats regarding patients created in June whatever the year.</p>




<h2>Using the library</h2>

<p>Now that we understand the theory, let's jump into the coding.</p>

<p>First, <code>DB_Stats</code> is compatible with the Mouf framework, so any object created below by code can also be created using the Mouf graphical interface.</p>

<h3>Creating the DB_Stats object</h3>
<p>In this part, we will focus on creating the objects programmatically.</p>
<p>In order to create a roll-up table, we need to define a <code>DB_Stats</code> object.</p>


<pre>
+--------------------------------------------------------+
| DB_Stats                                               |
+--------------------------------------------------------+
| + setDbConnection(DB_Connection connection)            |
| + setSourceTable(string tableName)                     |
| + setStatsTable(string tableName)                      |
| + setDimensions(array&lt;DB_Dimension&gt; dimensions)        |
| + addDimension(DB_Dimension dimension)                 |
| + setValues(array&lt;DB_Stat_Column&gt; values)              |
| + addValue(DB_Stat_Column value)                       |
| + createStatsTable()                                   |
| + fillTable()                                          |
| + createTrigger()                                      |
+--------------------------------------------------------+
</pre>

<p>The first step is to get a connection to the database. This is done via the <code>DB_Connection object</code>:</p>

<pre class="brush:php">
$conn = new DB_MySqlConnection();
$conn-&gt;setHost("localhost");
$conn-&gt;setDbName("myDb");
$conn-&gt;setUser("root");
$conn-&gt;connect();

$dbStats = new DB_Stats();
$dbStats-&gt;setDbConnection($conn);
$dbStats-&gt;setSourceTable("patients");
$dbStats-&gt;setStatsTable("patientsrollup");
</pre>

<p>With this piece of code, we create a connection to MySql, we create a Db_Stats object and we connect it to the connection.</p>
<p>Then, we set the source table and the rollup table.</p>

<h3>Creating the dimensions</h3>
<p>Now, we need to set the dimensions. For this, we will use the <code>DB_Stats-&gt;addDimension</code> method. This method takes a DB_Dimension object in argument.</p>

<pre>
+--------------------------------------------------------+
| DB_Dimension                                           |
+--------------------------------------------------------+
| + setColumns(array&lt;DB_StatColumn&gt; columns)             |
| + addColumn(DB_StatColumn column)                      |
+--------------------------------------------------------+
</pre>

<p>And a dimension is made of a set of columns.</p>

<pre>
+--------------------------------------------------------+
| DB_StatColumn                                          |
+--------------------------------------------------------+
| + setColumnName(string name)                           |
| + setDataOrigin(string dataOrigin)                     |
| + setType(string type)                                 |
+--------------------------------------------------------+
</pre>

Here is a sample of declaration for the "Job" dimension:

<pre class="brush:php">
$jobColumn = new DB_StatColumn();
$jobColumn-&gt;setColumnName("job");
$jobColumn-&gt;setDataOrigin("[statcol].job");
$jobColumn-&gt;setType("VARCHAR(255)");

$jobDimension = new DB_Dimension();
$jobDimension-&gt;addColumn($jobColumn);

$dbStats-&gt;addDimension($jobDimension);
</pre>

<p>It is important to understand that each "column" declared in a dimension will result in a column created in the rollup table.
The type of the column is set using the <code>DB_StatColumn-&gt;setType</code> method.</p>
<p>So by creating the "job" dimension, we add the "job" column to the rollup table. The <em>dataOrigin</em> refers to the 
place the data comes from. It will come from the "job" column of the source table. We refer to this column as "[statcol].job".</p>

<p><em>Important!</em><br/>
When refering to a column from the source table, ALWAYS prefix the name of the column by "[statcol].".</p> 

<h3>Creating the dimensions - advanced</h3>

<p>We created a easy dimension, with only one column, but many dimensions are not that easy. Let's focus on the date dimension.</p>
<p>In the "date" dimension, a single field in the source table provides data for many columns. Here is the dimension:</p>

<pre class="brush:php">
$year = new DB_StatColumn();
$year-&gt;setColumnName("year");
$year-&gt;setDataOrigin("YEAR([statcol].CreationDate)");
$year-&gt;setType("INT");

$month = new DB_StatColumn();
$month-&gt;setColumnName("month");
$month-&gt;setDataOrigin("MONTH([statcol].CreationDate)");
$month-&gt;setType("INT");

$day = new DB_StatColumn();
$day-&gt;setColumnName("day");
$day-&gt;setDataOrigin("DAY([statcol].CreationDate)");
$day-&gt;setType("INT");

$dateDimension = new DB_Dimension();
// Remember the order is important. We add the year first, then the month and finally the day
$dateDimension-&gt;addColumn($year);
$dateDimension-&gt;addColumn($month);
$dateDimension-&gt;addColumn($day);

$dbStats-&gt;addDimension($dateDimension);
</pre>

<p>In this sample, have a look at the dataOrigin. It is dynamically computed using MySql functions. For instance, <code>YEAR([statcol].CreationDate)</code> will
return the year that is stored in the <code>CreationDate</code> column.</p>

<h2>Creating the values</h2>

<p>We created the dimension columns, now, we need to create the values.</p>
<p>The values are simply a set of <code>DB_StatColumn</code> objects bound to the DB_Stats object.</p>

<p>Here is an example:</p>
<pre class="brush:php">
$totalAge = new DB_StatColumn();
$totalAge-&gt;setColumnName("sumage");
$totalAge-&gt;setDataOrigin("[statcol].age");
$totalAge-&gt;setType("BIGINT");

$dbStats-&gt;addValue($totalAge);
</pre>

<p>This will create a "sumage" column in the stats table that will contain the sum of the ages of the patients. The dataOrigin is similar to the one we saw with the dimensions.
You MUST prefix any column from the source table with "[statcol].".</p>

<p>You can use any MySql function in the dataOrigin field to perform transformations. You can also use
serveral columns from the source table, etc...
For instance, we could compute the sum of the body mass indexes, using a dataOrigin computed this way:</p>
<pre class="brush:php">
$bodyMassIndex-&gt;setDataOrigin("[statcol].weight/([statcol].height*[statcol].height)");
</pre>

<p>Special case:<p>
<p>The "cnt" column is a special case. Indeed, the column does not refer to any field, it is just incremented by one when a new row is added. In this case, you can just provide
"1" as a value for dataOrigin. Therefore, 1 will be added to the "cnt" column each time a new row matches the stats.</p>

<pre class="brush:php">
$cnt = new DB_StatColumn();
$cnt-&gt;setColumnName("cnt");
$cnt-&gt;setDataOrigin("1");
$cnt-&gt;setType("BIGINT");

$dbStats-&gt;addValue($cnt);
</pre>

<h2>Creating the tables and triggers</h2>

<p>Our <code>DB_Stats</code> object is now fully configured. We just have to execute a few methods to set the system up.</p>

<pre class="brush:php">
// Creates the stats table
$dbStats-&gt;createStatsTable();

// Fills the table with stats
$dbStats-&gt;fillTable();

// Creates the triggers
$dbStats-&gt;createTrigger();
</pre>

<p>The <code>DB_Stats-&gt;createStatsTable</code> will create the table in the database.</p>
<p>The <code>DB_Stats-&gt;fillTable</code> will purge the stats table and fill it with freshly computed stats from the source table. Beware, if the source table is big, this operation might take some time!</p>
<p>The <code>DB_Stats-&gt;createTrigger</code> will create the triggers on the source table that will automatically update the stats table.</p>

<p>That's it! Now, we have a perfectly functional rollup table that we can query to retrieve stats from the source table.
The rollup table is already indexed and will deliver results at the speed of light!</p>

<p>You can perform queries directly on that table, or you can use the <a href="DB_Stats_Query.html"><code>DB_Stats_Query</code></a> class that is designed for performing queries on stats tables.</p>

<h2>Known limitations</h2>

<p>Due to the way DB_Stats works, you should be careful when developing with DB_Stats about those issues:</p>
<ul>
	<li>DB_Stats does not support performing stats on several tables. You cannot JOIN several tables and compute stats on those JOINed tables (so it is impossible to draw a snowflake or star schema like in OLAP)</li>
	<li>Since DB_Stats writes in the stats table in real time, the writing process in the source table might be severely slowed, especially if you have a big number of dimensions. Try to avoid to many dimensions, or be sure to use those on a table that is not modified to often.</li>
	<li>Values can only be added. You can compute the average by dividing the sum of the value by the sum of the number of lines, but you cannot compute the "max" or the "min" of a column.</li>
</ul>