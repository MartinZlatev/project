/* when the site is loaded, get the user data from the backend and attach it on the page */

(async function() {
    const data =  await getUserData()
    if (data["status"] === "SUCCESS") {
        renderUserData(data["data"]);
    }
    else if (data["status"] == "UNAUTHENTICATED") { // if there is no session or cookies, return the user to the login page
        window.location.replace("../login/login_form.html");
    }
    else {
        throw new Error(data["message"]);
    }

    renderUserName(data["data"]);
})()

// send a GET request to the backend in order to recieve the user's data
async function getUserData() {
    const response = await fetch("../../server/api/user_data/load_user_data.php");
    const data = await response.json();
    return data;
}

function renderUserData(data) {
    const firstnamePar = document.getElementById("name");
    const emailPar = document.getElementById("email");
    const course = document.getElementById("course");
    const major = document.getElementById("major");
    const image = document.getElementById("profile-image");

    image.src = data["path"] ?  `../../server/images/${data["path"]}` : '../../server/images/default.png';
    firstnamePar.innerHTML = `Име: ${data["name"]}`;
    emailPar.innerHTML = `E-mail: ${data["email"]}`;
    course.innerHTML = `Курс: ${data["course"]}`;
    major.innerHTML = `Специалност: ${data["major"]}`;
}

function renderUserName(data){
    const headerName = document.getElementById("header-name");
    headerName.innerText = data["name"];
}