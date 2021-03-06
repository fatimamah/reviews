<?php include("includes/init.php");



$db = open_sqlite_db("secure/catalog.sqlite");

$messages = array();

function print_courses($courses)
{
?>
  <tr>
    <td><?php echo htmlspecialchars($courses["course_name"]); ?></td>
    <td><?php echo htmlspecialchars($courses["semester"]); ?></td>
    <td><?php echo htmlspecialchars($courses["professor"]); ?></td>
    <td><?php echo htmlspecialchars($courses["credits"]); ?></td>
    <td><?php echo htmlspecialchars($courses["reqs"]); ?></td>
    <td><?php echo htmlspecialchars($courses["comments"]); ?></td>

      <?php
      if (($courses["recommended"]) == "yes"){
        echo "<td class= \"yes\">✔</td>";
    }
    else if(($courses["recommended"]) == "no"){
      echo "<td class=\"no\">X</td>";
    }
    else {echo "<td class=\"other\">Not valid</td>";
    }
      ?>

  </tr>
  <?php
}

const SEARCH_FIELDS = [
  "all" => "Search Everything",
  "course_name" => "Search Course Names",
  "semester" => "Search Semester",
  "professor" => "Search Professors",
  "credits" => "Search Credits",
  "reqs" => "Search Distribution/Breadth",
  "comments" => "Search Comments",
  "recommended" => "Search Recommended"
];


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

// Insert Form


$courseslist = exec_sql_query($db, "SELECT DISTINCT course_name FROM courses", NULL)->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $valid_review = TRUE;

  $course_name = filter_input(INPUT_POST, 'course_name', FILTER_SANITIZE_STRING);
  //var_dump($course_name);
  $semester = filter_input(INPUT_POST, 'semester', FILTER_SANITIZE_STRING);
  //var_dump($semester);
  $professor = filter_input(INPUT_POST, 'professor', FILTER_SANITIZE_STRING);
  //var_dump($professor);
  $credits = filter_input(INPUT_POST, 'credits', FILTER_VALIDATE_INT);
  //var_dump($credits);
  $reqs = filter_input(INPUT_POST, 'reqs', FILTER_SANITIZE_STRING);
  //var_dump($reqs);
  $comments = filter_input(INPUT_POST, 'comments', FILTER_SANITIZE_STRING);
  //var_dump($comments);
  $recommended = filter_input(INPUT_POST, 'recommended', FILTER_SANITIZE_STRING);
  //var_dump($recommended);



  if ($recommended != "yes" && $recommended != "no" ) {
   $valid_review = FALSE;
  }

  if ($course_name == ''){
    $valid_review=FALSE;
  }

  if ($professor == ''){
    $valid_review=FALSE;
  }

  if ($credits<= 0){
    $valid_review=FALSE;
  }


  //insert reviews
  if ($valid_review) {

    $sql="INSERT INTO courses (course_name, semester, professor, credits, reqs, comments, recommended) VALUES (:course_name, :semester, :professor, :credits, :reqs, :comments, :recommended)";
    $params= array(':course_name' => $course_name, ':semester'=> $semester, ':professor' => $professor, ':credits' => $credits, ':reqs' => $reqs, ':comments'=> $comments, ':recommended'=> $recommended);
    $result = exec_sql_query($db, $sql, $params);


    if ( $result) {
      array_push($messages, "Your course recommendation has been recorded. Thank you!");
    } else {
      array_push($messages, "Failed to add course recommendation.");
    }
  } else {
    array_push($messages, "Failed to add course recommendation.");
  }
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

  <h1> Cornell Sociology Class Reviews</h1>

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

            if ($search_field == "all") {
                $sql = "SELECT * FROM courses WHERE (course_name LIKE '%' || :search || '%') OR (semester LIKE '%' || :search  || '%') OR (professor LIKE '%' || :search  || '%') OR (credits LIKE '%' || :search  || '%') OR (reqs LIKE '%' || :search  || '%') OR (comments LIKE '%' || :search  || '%') OR (recommended LIKE '%' || :search  || '%')";
                $params= array(':search' => $search);
            } else {
                $sql = "SELECT * FROM courses WHERE ($search_field LIKE '%' || :search || '%')";
                $params= array(':search' => $search);
            }
        } else {
          ?>
          <h2>All Reviews</h2>
          <?php

          $sql = "SELECT * FROM courses";
          $params = array();
        }

    $result = exec_sql_query($db, $sql, $params);
    if ($result) {

      $records = $result->fetchAll();

      if (count($records) > 0) {

    ?>
    <table>
      <tr>
        <th>Course</th>
        <th>Semester</th>
        <th>Professor</th>
        <th>Credits</th>
        <th>Distribution/Breadth</th>
        <th> Comments </th>
        <th>Recommended</th>
      </tr>

      <?php
        foreach($records as $courses) {
          print_courses($courses);
        }
        ?>
    </table>
    <?php
    } else {

      echo "<p>No matching course recommendations found.</p>";
    }
  }
  ?>

<?php

foreach ($messages as $message) {
  echo "<p class= \"messages\"><strong>" . htmlspecialchars($message) . "</strong></p>\n";
}
?>

  <h2>Review a Class</h2>


  <form id="reviewCourse" action="index.php" method="post" novalidate>
    <div class="group_label_input">
      <label>Course Name:</label>
      <input type="text" name="course_name" />
    </div>

    <div class="group_label_input">
      <label name="semester">Semester:</label>
      <div>
        <input id="fall" type="checkbox" name="semester" value="Fall" checked /><label for="fall">Fall</label>
        <input id="spring" type="checkbox" name="semester" value="Spring" /><label for="spring">Spring</label>
        <input id="winter" type="checkbox" name="semester" value="Winter" /><label for="winter">Winter</label>
        <input id="summer" type="checkbox" name="semester" value="Summer" /><label for="summer">Summer</label>
      </div>
    </div>

    <div class="group_label_input">
      <label>Professor:</label>
      <input type="text" name="professor" />
    </div>

    <div class="group_label_input">
      <label>Credits:</label>
      <input type="number" name="credits" min=0 />
    </div>

    <div class="group_label_input">
      <label>Distribution/Breadth:</label>
      <input type="text" name="reqs" />
    </div>

    <div class="group_label_input">
      <label>Comments:</label>
      <textarea name="comments" cols="40" rows="5"></textarea>
    </div>

    <div class="group_label_input">
      <label name="recommended">Do you recommend this course to other students?</label>
      <div>
        <input id="yes" type="radio" name="recommended" value="yes" checked /><label for="yes">yes</label>
        <input id="no" type="radio" name="recommended" value="no" /><label for="no">no</label>
        </div>
    </div>

    <div class="group_label_input">
      <span>
        <!-- empty element; used to align submit button --></span>
      <button type="submit">Add a Course Review</button>
    </div>
    </li>
      </ul>
   </form>


  </main>

</body>

</html>
