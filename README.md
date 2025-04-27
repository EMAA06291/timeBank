<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .feedback-container {
            text-align: center;
            padding: 40px;
            border: 1px solid #eee;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 350px;
            background-color: #ffffff;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        p {
            color: #555;
            margin-bottom: 20px;
        }

        .stars {
            font-size: 30px;
            color: rgb(255, 60, 0);
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        label {
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"] {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: rgb(255, 60, 0);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: rgb(255, 140, 0);
        }

        input::placeholder {
            color: #333;
            opacity: 1;

        }
    

    </style>
</head>
<body>
    <div class="feedback-container">
        <h1>We’d Love your feedback!</h1>
        <p>Help us improve TimeBank by rating experience.</p>

        <div class="stars">
            &#9733; &#9733; &#9733; &#9733; &#9733;
        </div>

        <form>
            <label for="like">What did you like most about TimeBank?</label>
            <input type="text" id="like" name="like" placeholder="Your answer">

            <label for="suggestion">Any suggestion to make it better?</label>
            <input type="text" id="suggestion" name="suggestion" placeholder="Your suggestion">

            <button type="submit">Submit Feedback</button>
        </form>
    </div>
</body>
</html>
