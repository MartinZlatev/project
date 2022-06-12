(function() {
    const input = document.getElementById("upload-image-input");
    const form = document.getElementById("upload-image-form"); 
    const responseDiv = document.getElementById("upload-response-message"); // div which will contain response message

    form.addEventListener('submit', (event) => {
        event.preventDefault(); // prevent the form from resetting

        // hide the previous message because we may create another one in it's place
        responseDiv.classList.add("no-show");

        // gather the inputted data
        const data=input.value;
        // clear the contents from the previous message
        responseDiv.innerHTML = null;

        // impoad image to the server
        uploadImage(data)
        .then((responseMessage) => {
            if (responseMessage["status"] === "ERROR") {
                throw new Error(responseMessage["message"]);
            }
            createErrorDivContent(responseDiv, "Успешно качихте снимката");
        })
        .catch((errorMessage) => {
            console.log('here');
            createErrorDivContent(responseDiv, errorMessage); // if the upload resulted in an error, then display an error messages
        })
    })
})()

/* sends the inputted data over to the backend to upload the image */
async function uploadImage(data) {        
    console.log(JSON.stringify(data));
    const response = await fetch("../../server/api/upload.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(data)
    });
    const data_1 = await response.json();
    console.log(data_1);
    return data_1;
}

// ??
function createErrorDivContent(div, response) {
    div.innerHTML = response
}