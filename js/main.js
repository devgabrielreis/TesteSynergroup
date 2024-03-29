let clientsTableBody = document.querySelector(".clients-table-body");
let newClientPopup = document.querySelector(".new-client-popup");
let newClientPopupCloseBtn = document.querySelector(".new-client-popup-close-btn");
let clientViewPopup = document.querySelector(".client-view");
let clientViewCloseBtn = document.querySelector(".close-information-popup");
let reloadBtn = document.querySelector(".reload-btn");
let addClientBtn = document.querySelector(".add-client-btn");
let editClientBtn = document.querySelector(".edit-client-btn");
let removeClientBtn = document.querySelector(".delete-btn");
let editClientPopup = document.querySelector(".edit-client-popup");
let closeEditClientPopupBtn = document.querySelector(".edit-client-popup-close-btn");
let clients = [];
let currencies = [];

async function submitEditClient()
{
    let oldCode = document.querySelector(".edit-client-old-code").value;
    let newCode = document.querySelector(".edit-client-code").value;
    let clientName = document.querySelector(".edit-client-name").value;
    let currencyCode = document.querySelector(".edit-client-currency-code").value;
    let lastSaleDate = document.querySelector(".edit-client-last-sale-date").value;
    let totalSales = document.querySelector(".edit-client-total-sales").value;

    const url = "/api/client/update.php";

    const requestBody = {
        oldCode: oldCode,
        newCode: newCode,
        clientName: clientName,
        currencyCode: currencyCode,
        lastSaleDate: lastSaleDate,
        totalSales: totalSales
    }

    try
    {
        const response = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(requestBody)
        });

        if(response.status === 200)
        {
            loadPage();
            alert("Cliente atualizado com sucesso");
            clientViewPopup.style.display = "none";
            editClientPopup.style.display = "none";
        }
        else
        {
            const jsonResponse = await response.json();
            let apiResponse = jsonResponse;
            alert(apiResponse.error);
        }
    }
    catch(error)
    {
        alert("Erro no fetch");
        console.log("Erro no fetch", error);
    }
}

closeEditClientPopupBtn.onclick = function ()
{
    editClientPopup.style.display = "none";
}

editClientBtn.onclick = function ()
{
    editClientPopup.style.display = "block";
    document.querySelector(".client-name-edit-popup").innerHTML = document.querySelector(".client-name").innerHTML;

    let currencySelector = document.querySelector(".edit-client-currency-code");

    currencySelector.innerHTML = '';

    for(let currency of currencies)
    {
        let entry = document.createElement("option");
        entry.innerText = currency.abbreviation;
        entry.value = currency.code;
        currencySelector.appendChild(entry);
    }

    document.querySelector(".edit-client-old-code").value = document.querySelector(".client-code").innerHTML;
    document.querySelector(".edit-client-code").value = document.querySelector(".client-code").innerHTML;
    document.querySelector(".edit-client-name").value = document.querySelector(".client-name").innerHTML;
    document.querySelector(".edit-client-currency-code").value = document.querySelector(".client-currency").innerHTML;
    document.querySelector(".edit-client-last-sale-date").value = document.querySelector(".client-last-sale-date").innerHTML;
    document.querySelector(".edit-client-total-sales").value = document.querySelector(".client-total-sales").innerHTML;
}

removeClientBtn.onclick = async function()
{
    let clientName = document.querySelector(".client-name").innerHTML;
    let clientCode = document.querySelector(".client-code").innerHTML;
    if(confirm("Você tem certeza que deseja remover o cliente \"" + clientName + "\" do banco de dados?"))
    {
        const url = "/api/client/remove.php";

        const requestBody = {
            code: clientCode,
        }

        try
        {
            const response = await fetch(url, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(requestBody)
            });

            if(response.status === 200)
            {
                alert("Cliente removido com sucesso");
                loadPage();
                clientViewPopup.style.display = "none";
            }
            else
            {
                const jsonResponse = await response.json();
                let apiResponse = jsonResponse;
                alert(apiResponse.error);
            }
        }
        catch(error)
        {
            alert("Erro no fetch");
            console.log("Erro no fetch", error);
        }
    }
}

addClientBtn.onclick = function ()
{
    newClientPopup.style.display = "block";

    let currencySelector = document.querySelector(".new-client-currency-code");

    currencySelector.innerHTML = '';

    for(let currency of currencies)
    {
        let entry = document.createElement("option");
        entry.innerText = currency.abbreviation;
        entry.value = currency.code;
        currencySelector.appendChild(entry);
    }
}

clientViewCloseBtn.onclick = function ()
{
    clientViewPopup.style.display = "none";
}

newClientPopupCloseBtn.onclick = function ()
{
    newClientPopup.style.display = "none";
}

function formatDate(dateStr)
{
    let date = dateStr.split("-");

    let day = date[2];
    let month = date[1];
    let year = date[0];

    return `${day}/${month}/${year}`;
}

function onEntryClick()
{
    clientViewPopup.style.display = "block";

    document.querySelector(".client-name").innerHTML = this.querySelector(".client-name-cell").innerHTML;
    document.querySelector(".client-code").innerHTML = this.querySelector(".code-cell").innerHTML;
    document.querySelector(".client-currency").innerHTML = this.querySelector(".currency-code-cell").innerHTML;
    document.querySelector(".client-creation-date").innerHTML = this.querySelector(".creation-date-cell").innerHTML;
    document.querySelector(".client-last-sale-date").innerHTML = this.querySelector(".last-sale-date-cell").innerHTML;
    document.querySelector(".client-total-sales").innerHTML = this.querySelector(".total-sales-cell").innerHTML;
}

function clearTable()
{
    clientsTableBody.innerHTML = '';
}

function addClientToTable(client)
{
    let row = document.createElement("tr");
    row.classList.add("clients-table-entry");

    let codeCell = document.createElement("td");
    codeCell.textContent = client.code;
    codeCell.classList.add("code-cell");
    row.appendChild(codeCell);

    let clientNameCell = document.createElement("td");
    clientNameCell.textContent = client.clientName;
    clientNameCell.classList.add("client-name-cell");
    row.appendChild(clientNameCell);

    let currencyCodeCell = document.createElement("td");
    currencyCodeCell.textContent = client.currencyAbbreviation;
    currencyCodeCell.classList.add("currency-code-cell");
    row.appendChild(currencyCodeCell);

    let creationDateCell = document.createElement("td");
    creationDateCell.textContent = formatDate(client.creationDate);
    creationDateCell.classList.add("creation-date-cell");
    row.appendChild(creationDateCell);

    let lastSaleDateCell = document.createElement("td");
    if(client.lastSaleDate === null)
    {
        lastSaleDateCell.textContent = "-";
    }
    else
    {
        lastSaleDateCell.textContent = formatDate(client.lastSaleDate);
    }
    lastSaleDateCell.classList.add("last-sale-date-cell");
    row.appendChild(lastSaleDateCell);

    let totalSalesCell = document.createElement("td");
    totalSalesCell.textContent = client.totalSales.toFixed(client.currencyDecimalPlaces);
    totalSalesCell.classList.add("total-sales-cell");
    row.appendChild(totalSalesCell);

    row.onclick = onEntryClick;

    clientsTableBody.appendChild(row);
}

function loadClientsTable()
{
    clearTable();

    for(let client of clients)
    {
        addClientToTable(client);
    }
}

async function getClients()
{
    const url = "/api/client/get.php";

    try
    {
        const response = await fetch(url);

        if(response.status !== 200)
        {
            alert("Algo deu errado placeholder");
            clients = [];
        }
        else
        {
            const jsonResponse = await response.json();
            clients = jsonResponse;
        }
    }
    catch(error)
    {
        console.error("Erro no fetch placeholder", error);
    }
}

async function getCurrencies()
{
    const url = "/api/currency/get.php";

    try
    {
        const response = await fetch(url);

        if(response.status !== 200)
        {
            alert("Algo deu errado placeholder");
            clients = [];
        }
        else
        {
            const jsonResponse = await response.json();
            currencies = jsonResponse;
        }
    }
    catch(error)
    {
        console.error("Erro no fetch", error);
    }
}

async function loadPage()
{
    await getClients();
    await getCurrencies();
    loadClientsTable();
}

reloadBtn.onclick = () => loadPage();

async function submitNewClient()
{
    let clientCode = document.querySelector(".new-client-code").value;
    let clientName = document.querySelector(".new-client-name").value;
    let clientCurrencyCode = document.querySelector(".new-client-currency-code").value;
    
    const url = "/api/client/add.php";

    const requestBody = {
        code: clientCode,
        clientName: clientName,
        currencyCode: clientCurrencyCode
    }

    try
    {
        const response = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(requestBody)
        });

        if(response.status === 200)
        {
            loadPage();
            alert("Cliente adicionado com sucesso");
            newClientPopup.style.display = "none";
        }
        else
        {
            const jsonResponse = await response.json();
            let apiResponse = jsonResponse;
            alert(apiResponse.error);
        }
    }
    catch(error)
    {
        alert("Erro no fetch");
        console.log("Erro no fetch", error);
    }
}

function filterClients()
{
    let search = document.querySelector(".search-bar").value;

    search = search.toLowerCase()
    
    clearTable();

    for(let client of clients)
    {
        if(client.code.toLowerCase().includes(search) || client.clientName.toLowerCase().includes(search))
        {
            addClientToTable(client);
        }
    }
}

loadPage();
