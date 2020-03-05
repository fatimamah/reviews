<?php include("includes/init.php");


// open connection to database
// TODO: create $db variable by opening the database.
$db = open_sqlite_db("secure/courses.sqlite");
function print_record($record)
{
?>
  <tr>
    <td><?php echo htmlspecialchars($record["name"]); ?></td>
    <td><?php echo htmlspecialchars($record["prereqs"]); ?></td>
    <td><?php echo htmlspecialchars($record["semester"]); ?></td>
    <td><?php echo htmlspecialchars($record["professor"]); ?></td>
    <td><?php echo htmlspecialchars($record["credits"]); ?></td>
    <td><?php echo htmlspecialchars($record["reqs"]); ?></td>

  </tr>
<?php
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <link rel="stylesheet" type="text/css" href="styles/style.css" media="all"/>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Sociology Courses</title>
</head>

<body>

  <!-- TODO: This should be your main page for your site. Remove this file when you're ready!-->
  <h1> Cornell Sociology Class Roster </h2>
  <main>

    <?php $sql = "SELECT * FROM courses;";
    $result = exec_sql_query($db, $sql); ?>
    <table>
      <tr>
        <th>Course</th>
        <th>Prerequisites</th>
        <th>Semester</th>
        <th>Professor</th>
        <th>Credits</th>
        <th>Distribution/Breadth</th>
      </tr>

      <?php $records = $result->fetchAll();
        foreach($records as $record) {
           print_record($record);
        }?>
    </table>
    <?php
    ?>
  </main>

</body>

</html>
