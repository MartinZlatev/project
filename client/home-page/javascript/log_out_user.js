/* when the user clicks on the "Изход" option, log him out by:
   1. send a GET request to the backend, signalling that it should destroy the user's session
   2. redirect the user to the log in page
*/

(function() {
    const logOutOption = document.getElementById("log-out");
    console.log(logOutOption);

    logOutOption.addEventListener("click", () => {
        logOut()
        .then((response) => {
            if (response["status"] === "SUCCESS") {
                window.location.replace("../login/login.html");
            }
            else {
                throw new Error(response["message"]);
            }
        })
        .catch((error) => {
            console.log(error);
        })
    })
})()

async function logOut() {
    return fetch("../../server/api/logout_user/delete_user_session.php")
    .then((response) => {return response.json()})
    .then((data) => {return data})
}