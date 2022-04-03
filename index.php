<!DOCTYPE html>  
<html>  
    <head>  
        <script>
            function fun() {
                let _data = {
                    actionCode: "99868",
                    actionNodeId: 1,
                    staffCode: "St001",
                    password: "1qazZAQ!2022"
                }
                fetch('https://khounkhamlogistic.com/TTMS/api/authen/login.php', {
                    method: "POST",
                    body: JSON.stringify(_data),
                    headers: {"Content-type": "application/json; charset=UTF-8"}
                })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Success:', data);
                        })
                        .catch((error) => {
                            console.error('Error:', error);
                        });
            }
        </script>  
    </head>  
    <body>  
        <h3> This is an example of using onclick attribute in HTML. </h3>  
        <p> Click the following button to see the effect. </p>  
        <button onclick = "fun();">Click me</button>  
    </body>  
</html>  



