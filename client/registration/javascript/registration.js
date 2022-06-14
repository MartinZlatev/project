(function() {
    const form = document.getElementById("registration-form"); // the registration form
    const inputs = document.querySelectorAll("input, select"); // the input fields and the select one

    const responseDiv = document.getElementById("response-message"); // the div that will contain the error message if the backend returned an error

    const toLoginBtn = document.getElementById("to-login-btn"); // button, redirecting to the login page

    toLoginBtn.addEventListener('click', () => {
        window.location.href = "../login/login.html"; // redirect the user over to the registration page with an option to go back from the browser
    });

    form.addEventListener('submit', (event) => {
        event.preventDefault(); // prevent the form from resetting

        // remove styles from last error message
        responseDiv.classList.remove("error");

        // remove last error message
        responseDiv.innerHTML = null;

        // gather all the input information
        let data = {};
        inputs.forEach(input => {
            data[input.name] = input.value;
        })

        sendFormData(data)
        .then((responseMessage) => {
            if (responseMessage["status"] === "ERROR") {
                throw new Error(responseMessage["message"]);
            }
            else {
                window.location.replace("../home-page/home.html"); // redirect user to his newly created account
            }
        })
        .catch((errorMsg) => {
            showDiv(responseDiv, errorMsg); // create an error message if the server returned an error
        })
    })
})();

/* send the inputted data over to the backend and based on the server's response, display an error message or redirect user to his newly created account */
async function sendFormData(data) {
    const response = await fetch("../../server/api/registration/register-user.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(data)
    });
    const data_2 = await response.json();
    return data_2;
}


function showDiv(div, message) {

    div.classList.add("error");
    div.classList.remove("no-display");

    let messageContainer = document.createElement("span");
    let responseMessage = document.createElement("p");
    responseMessage.textContent = message;
    messageContainer.appendChild(responseMessage);

    div.appendChild(messageContainer);
}