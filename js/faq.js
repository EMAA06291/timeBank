document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.question-box form');
    const input = form.querySelector('input');

    form.addEventListener('submit', function (e) {
        e.preventDefault(); // منع التحديث التلقائي للصفحة عند الضغط على زر submit

        const question = input.value.trim();
        if (question === '') {
            alert('Please enter a question.');
            return;
        }

        // إرسال السؤال باستخدام method POST
        fetch('http://localhost/timebank/php/submit_question_FAQ.php', {
            method: 'POST', // تأكد من إرسال الطلب كـ POST
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'question=' + encodeURIComponent(question) // إرسال السؤال في body الطلب
        })
        .then(response => response.text())
        .then(result => {
            alert(result); // عرض النتيجة اللي بترجع من السيرفر
            input.value = ''; // مسح الـ input بعد إرسال السؤال
        })
        .catch(error => {
            alert('Error: ' + error.message); // لو حصل خطأ أثناء الطلب
        });
    });
});

