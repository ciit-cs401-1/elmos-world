
document.addEventListener("DOMContentLoaded", function () {
    
    console.log("ShowBlade@michael_edit_comment - Content finished loading");

    console.log("ShowBlade@michael_edit_comment - START");


    const totalComments = document.querySelectorAll('[id^="initiate-edit-comment-"]');
    let isEditing = false; // Track state
    
    if (totalComments.length > 0) {
        totalComments.forEach((edit_comment_button) => {
            edit_comment_button.addEventListener('mouseup', function(event) {
                if (event.button === 0) {
                    console.log('Left mouse button released on edit button');

                    
                    // Step 1: Get the ID
                    console.log("Which button was it?", edit_comment_button.getAttribute("id")); 
                    const commentId = edit_comment_button.getAttribute("id").split("-").pop();
                    console.log("comment id?", commentId); 

                    // Step 2: Query the "id" property of the div tag 
                    const myDiv = document.getElementById(`comment-${commentId}`);
                    console.log(myDiv.getAttribute("id"));

                    const myDivPTag = myDiv.querySelector("p");
                    const myDivFormTag = myDiv.querySelector("form");

                    // Step 3: Toggle it's hidden class
                    console.log("is form not hidden?", isEditing);
                    try {
                        if (isEditing == false) {
                            console.log("showing the form")
                            isEditing = true;
                            myDivPTag.classList.add("hidden");
                            myDivFormTag.classList.remove("hidden");
                        } else if (isEditing == true) {
                            console.log("hiding the form")
                            isEditing = false;
                            myDivPTag.classList.remove("hidden");
                            myDivFormTag.classList.add("hidden");
                        }
                    } catch (e) {
                        console.log(`ShowBlade - ERROR: myDiv tag is missing : ${e}`);
                    }
                }
            });
        });
    }
});

