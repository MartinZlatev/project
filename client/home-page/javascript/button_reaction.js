 (function() {
    const options = document.querySelectorAll("div.option"); // returns "Изход" option so we need to be careful not to add another event listener to it
    const sections = document.querySelectorAll("#account-tab > section");
    for (let option of options) {

        option.addEventListener("click", () => {
            for (let section of sections) {
                
                if (option.id + "-section" === section.id) { // display only the corresponding section
                    section.classList.remove("no-display");
                }
                else { // hide the rest
                    section.classList.add("no-display");
                }
            }
        });
    }
})();