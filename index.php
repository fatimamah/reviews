<?php include("includes/init.php");


// open connection to database
// TODO: create $db variable by opening the database.
$db = open_sqlite_db("secure/catalog.sqlite");

function print_courses($courses)
{
?>
  <tr>
    <td><?php echo htmlspecialchars($courses["course_name"]); ?></td>
    <td><?php echo htmlspecialchars($courses["semester"]); ?></td>
    <td><?php echo htmlspecialchars($courses["professor"]); ?></td>
    <td><?php echo htmlspecialchars($courses["credits"]); ?></td>
    <td><?php echo htmlspecialchars($courses["reqs"]); ?></td>

  </tr>
  <?php
}

const SEARCH_FIELDS = [
  "all" => "Search Everything",
  "course_name" => "Search Course Names",
  "semester" => "Search Semester",
  "professor" => "Search Professors",
  "credits" => "Search Credits",
  "reqs" => "Search Distribution/Breadth"
];

$do_search = FALSE;
$category = NULL;
$search = NULL;

if (isset($_GET['search'])) {
  $do_search = TRUE;
  $category = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_STRING);
  if (in_array($category, array_keys(SEARCH_FIELDS))) {
    $search_field = $category;
  } else {
    array_push($messages, "Invalid category for search.");
    $do_search = FALSE;
  }
  $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
  $search = trim($search);
} else {
  $do_search = FALSE;
  $category = NULL;
  $search = NULL;
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


  <main>

  <h1> Cornell Sociology Class Roster </h1>

  <form id="searchForm" action="index.php" method="get" novalidate>

  <select name="category">
                <?php foreach (SEARCH_FIELDS as $field_name => $label) { ?>
                    <option value="<?php echo $field_name; ?>">
                        <?php echo $label; ?>
                    </option>
                <?php } ?>
            </select>
            <input type="text" name="search" required />
            <button type="submit">Search</button>
        </form>

        <?php if ($do_search) { ?>
            <h2>Search Results</h2>

            <?php
            $params = array(":search" => $search);
            if ($search_field == "all") {
                $sql = "SELECT * FROM courses WHERE (course_name LIKE '%' || :search || '%') OR (semester LIKE '%' || :search  || '%') OR (professor LIKE '%' || :search  || '%') OR (credits LIKE '%' || :search  || '%') OR (reqs LIKE '%' || :search  || '%')";
            } else {
                $sql = "SELECT * FROM courses WHERE (".$search_field." LIKE '%' || :search || '%')";
            }
        } else {
            $sql = "SELECT * FROM courses";
            $params = array();
        }
    $result = exec_sql_query($db, $sql, $params); ?>
    <table>
      <tr>
        <th>Course</th>
        <th>Semester</th>
        <th>Professor</th>
        <th>Credits</th>
        <th>Distribution/Breadth</th>
      </tr>

      <?php $courses = $result->fetchAll();
        foreach($courses as $courses) {
          print_courses($courses);
        }?>
    </table>
    <?php
    ?>
  </main>

</body>

</html>
