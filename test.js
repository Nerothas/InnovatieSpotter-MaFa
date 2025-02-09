const https = require('node:https');
//const http = require('http');

async function requestJSON(urls){
    let arr = [];
    let options = {
        timeout: 5000,
    }
    try{
    await Promise.all(urls.map((url, index) => {
        return new Promise((resolve, reject) => {
            const req = https.request(url, options, (res) => {
                let body = '';
                res.on('data', (chunk) => {
                    body += chunk;
                });
                res.on('end', () => {
                    arr[index] = body
                    resolve();
                });
            });
                req.on('error', (e) => {
                reject(new Error(`problem with request: ${e.message}`));
                });
                req.end();

        }).catch((err) => {
            console.error(`Caught error for URL: ${url} - ${err.message}`)
        })
    }))
    } catch(err){
        console.error(`Unexpected error in processing the given URLs : ${err.message}`)
    }
    return arr;
}

let URLlist = ["https://dogapi.dog/api/v2/breeds?page[number]=3","https://dogapi.dog/api/v2/breeds?page[number]=4","asdf"]
let JSONresult = [];
(async () => {
    JSONresult = await requestJSON(URLlist)
    console.log(JSONresult[1])
})()









