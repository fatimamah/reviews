# Project 2: Design Journey

Be clear and concise in your writing. Bullets points are encouraged.

**Everything, including images, must be visible in VS Code's Markdown Preview.** If it's not visible in Markdown Preview, then we won't grade it.

## Catalog (Milestone 1)

### Describe your Catalog (Milestone 1)
> What will your collection be about? What types of attributes will you keep track of for the *things* in your catalog? 1-2 sentences.

My collection will be about Sociology classes at Cornell. It will have the days the classes meet, the semester (fall or spring), the professor, number of credits, and any college or major requirements it can fulfill.

### Target Audience(s) (Milestone 1)
> Tell us about your target audience(s).

My target audience is current Cornell undergraduate students who are Sociology majors and want to take Sociology classes at Cornell.

### Design Patterns (Milestone 1)
> Review some existing catalog that are similar to yours. List the catalog's you reviewed here. Write a small reflection on how you might use the design patterns you identified in your review in your own catalog.

https://classes.cornell.edu/browse/roster/SP20/subject/SOC

The Cornell Sociology class roster for Spring 2020 is similar. A design pattern that I may use are the letters M, T, W, R, and F for the different days of the week.

http://courses.cornell.edu/content.php?catoid=31&navoid=7991

The Cornell Archived course catalog. I could use the design pattern of listing the classes by the shortened name SOC then "-" the number and the full name of the class.



## Design & Planning (Milestone 2)

## Design Process (Milestone 2)
> Document your design process. Show us the evolution of your design from your first idea (sketch) to design you wish to implement (sketch). Show us the process you used to organize content and plan the navigation, if applicable.
> Label all images. All labels must be visible in VS Code's Markdown Preview.
> Clearly label the final design.

![alt:"first design"](first_design.jpg)
First Design


![alt:"second design"](second_design.jpg)
Final Design


## Partials (Milestone 2)
> If you have any partials, plan them here.

n/a

## Database Schema (Milestone 2)
> Describe the structure of your database. You may use words or a picture. A bulleted list is probably the simplest way to do this. Make sure you include constraints for each field.

Table: movies
- field 1: description..., constraints...
- field...

Table: courses(
    id: INTEGER{PK, U, Not, AI},
    name: TEXT{Not},
    pre_reqs{},
    semester: TEXT{Not},
    professor: TEXT{Not},
    credits: Integer{Not},
    reqs: TEXT{};
)


## Database Query Plan (Milestone 2)
> Plan your database queries. You may use natural language, pseudocode, or SQL.]

1. All records

    ```
    SELECT * FROM courses
    ```

2. Search records

    ```
    SELECT * FROM courses WHERE (condition)
    ```

3. Insert record

    ```
    INSERT INTO(name, pre_reqs, smester, professor, credits, reqs)
    VALUES(:value1,:value2,:value3,:value4,:value5,:value6)
    ```


## Code Planning (Milestone 2)
> Plan any PHP code you'll need here.

<? php
$db=open_sqlite_db("secure/courses.sqlite");

$sql="SELECT * FROM courses;"
$result = exec_sql_query($db, $sql);
?>


# Reflection (Final Submission)
> Take this time to reflect on what you learned during this assignment. How have you improved since Project 1? What things did you have trouble with?
