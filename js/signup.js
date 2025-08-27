function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function () {
        document.getElementById('preview').src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

function validateForm() {
    const fname = document.getElementById("fname").value.trim();
    const lname = document.getElementById("lname").value.trim();
    const pass = document.getElementById("pass").value;
    const confpass = document.getElementById("confpass").value;
    const email = document.getElementById("email").value.trim();
    const file = document.getElementById("fileInput").files[0];

    if (fname === "" || lname === "") {
        alert("الرجاء إدخال الاسم واللقب");
        return false;
    }

    if (!email.includes("@") || !email.includes(".")) {
        alert("يرجى إدخال بريد إلكتروني صالح");
        return false;
    }

    if (pass.length < 6) {
        alert("كلمة المرور يجب أن تكون على الأقل 6 أحرف");
        return false;
    }

    if (pass !== confpass) {
        alert("كلمتا المرور غير متطابقتين");
        return false;
    }

    if (!file) {
        alert("الرجاء اختيار صورة");
        return false;
    }

    return true;
}
