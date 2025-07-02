<?php


$examid = isset($_GET['examid'])? $_GET['examid'] : null;

if(!$examid){
    http_response_code(400); 
    echo "NO Exam found to show leaderboard";//TODO: show this message in nice HTML format
    exit;
}





// // Fetch categories
// $result = $conn->query("SELECT * FROM categories");
// $categories = [];
// while ($row = $result->fetch_assoc()) {
//     $categories[] = $row;
// }
// $result->free();

// // Fetch event quizzes
// $result = $conn->query("SELECT id, title, event_start_datetime FROM quizzes WHERE is_event_based = 1");
// $event_quizzes = [];
// while ($row = $result->fetch_assoc()) {
//     $event_quizzes[] = $row;
// }
// $result->free();
require 'includes/header.php';

$examquery = "select * from exams where id={$examid} limit 1 ";
$examresult = $conn->query($examquery);
if($examresult->num_rows){
    $examinfo = $examresult->fetch_assoc();
}

$query = "select l.*, e.title as examtitle, e.description as examdesc, e.duration as examduration, e.status as examstatus, u.username as username, u.first_name as firstname, u.last_name as lastname, u.avatar as avatar
from leaderboards l
left join exams e ON l.exam_id = e.id
left join users u ON l.user_id = u.id
where l.exam_id={$examid} order by l.total_score desc";
$result = $conn->query($query);
var_dump($result);

?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="assets/css/leaderboard_info.css">



<div class="container d-flex flex-column justify-content-center fixed" style="margin-top: 80px;">

    <h1 class="text-center mb-4">Leaderboard</h1>


        <!-- Tabs for Leaderboard Types -->
        <!-- Filters for Category, Class, Subject, Event -->
        <h1>Leaderboard For Exam: <?=$examinfo['title'] ?></h1>
        <p><?=$examinfo['description'] ?></p>
    <PRE>
        <?php
    // var_dump($examinfo);
        ?>
    </PRE>

        <!-- Leaderboard Table -->
        <div class="table-responsive">
            <table class="table table-striped leaderboard-table">
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>User</th>
                        <th>Score</th>
                        <th>Badges</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody id="leaderboardBody">
                    <?php
                    if($result->num_rows){
                        $count = 1;
                        while($record = $result->fetch_assoc()){
                            echo "
                            <tr>
                        <td>".$count++."</td>
                        <td><a href='userinfo.php?userid=".$record['user_id']."'>".$record['firstname']. " ".$record['lastname']."</a></td>
                        <td>".$record['total_score']."</td>
                        <td>Badges</td>
                        <td>Details</td>
                    </tr>
                            ";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
<?php include "includes/footer.php" ?>
<?php
// $conn->close();
?>