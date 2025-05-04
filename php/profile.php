<?php
include 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $profile_id = isset($_POST['profile_id']) ? intval($_POST['profile_id']) : null;
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : null;
    $skills = isset($_POST['skills']) ? trim($_POST['skills']) : null;
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $profile_pic = isset($_POST['profile_pic']) ? trim($_POST['profile_pic']) : null;
    $about_me = isset($_POST['about_me']) ? trim($_POST['about_me']) : null;

    if ($profile_id && $user_id && $name) {
        $user_check = $conn->prepare("SELECT COUNT(*) FROM users WHERE User_ID = ?");
        $user_check->bind_param("i", $user_id);
        $user_check->execute();
        $user_check->bind_result($user_exists);
        $user_check->fetch();
        $user_check->close();

        if ($user_exists == 0) {
            echo json_encode([
                "status" => "error",
                "message" => "User ID does not exist"
            ]);
            exit; 
        }

        $stmt = $conn->prepare("UPDATE my_profile SET skills = ?, name = ?, profile_pic = ?, about_me = ? WHERE profile_id = ? AND user_id = ?");
        $stmt->bind_param("ssssii", $skills, $name, $profile_pic, $about_me, $Profile_ID, $user_ID);

        if ($stmt->execute()) {
            echo json_encode([
                "status" => "success",
                "message" => "Profile updated successfully"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Failed to update profile"
            ]);
        }

        $stmt->close();
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Missing required fields"
        ]);
    }
}
?>