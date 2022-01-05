const created = 'CREATED'
const deleted = 'DELETED'

function myFunction(imgs) {
    // Get the expanded image
    let expandImg = document.getElementById("expandedImg");
    // Get the image text
    let imgText = document.getElementById("imgtext");
    // Use the same src in the expanded image as the image being clicked on from the grid
    expandImg.src = imgs.src;
  }
  
  let like = document.getElementById('like');

  let neededData = document.getElementById('user-info');
 
  like.addEventListener('click', (e) =>{
    let objectToDeal = e.currentTarget;
    fetch(neededData.dataset.urlHasUserLiked,
    {
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        method: "POST",
        body: JSON.stringify({user_id: neededData.dataset.loggedUser })
    })
    .then(res => res.json())
    .then(res => {
        console.log(objectToDeal)

        objectToDeal.classList.toggle('far')
        objectToDeal.classList.toggle('fas')
    })
    .catch(function(res){ console.log(res) })

  } )