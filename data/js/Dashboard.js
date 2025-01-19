function updateDashboard(){
    const productsTileValue = document.getElementById("productsTileValue");
    const categoriesTileValue = document.getElementById("categoriesTileValue");
    const clientsTileValue = document.getElementById("clientsTileValue");
    const invoiceTileValue = document.getElementById("invoiceTileValue");

    howManyProducts()
        .then(ammountProducts => {
            productsTileValue.textContent= ammountProducts;
        }).catch(error => {
        console.log('Error fetching ammount of products for dashboard: ', error);
    })
    howManyCategories()
        .then(ammountCategoires => {
            categoriesTileValue.textContent= ammountCategoires;
        }).catch(error => {
        console.log('Error fetching ammount of products for dashboard: ', error);
    })
    howManyClients()
        .then(ammountClients => {
            clientsTileValue.textContent= ammountClients;
        }).catch(error => {
        console.log('Error fetching ammount of products for dashboard: ', error);
    })
    howManyInvoices()
        .then(ammountInvoices => {
            invoiceTileValue.textContent= ammountInvoices;
        }).catch(error => {
        console.log('Error fetching ammount of products for dashboard: ', error);
    })


}

