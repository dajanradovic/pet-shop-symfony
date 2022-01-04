
function myFunction(imgs) {
    // Get the expanded image
    let expandImg = document.getElementById("expandedImg");
    // Get the image text
    let imgText = document.getElementById("imgtext");
    // Use the same src in the expanded image as the image being clicked on from the grid
    expandImg.src = imgs.src;
  }
  
  let like = document.getElementById('like');

  let user = document.getElementById('user-info');
  console.log(user)
  console.log('user', user.dataset.loggedUser)
  console.log('pet', user.dataset.currentPet)

  like.addEventListener('click', (e) =>{



    $.post("demo_test_post.asp",
  {
    target: "11",
    author: "1"
  },
  function(data, status){
    alert("Data: " + data + "\nStatus: " + status);
  });

      e.currentTarget.classList.toggle('far')
      e.currentTarget.classList.toggle('fas')


  } )