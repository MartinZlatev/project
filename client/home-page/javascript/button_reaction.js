 (function() {
    const options = document.querySelectorAll("div.option"); 
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
                
                if(option.id === 'my-account'){
                    window.location.reload();
                }
            }
        });
    }
})();