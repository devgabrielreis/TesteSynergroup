let clientsTableBody = document.getElementsByClassName("clients-table-body")[0];
let clients = [];
let currencies = [];

function formatDate(dateStr) {
    let date = new Date(dateStr);

    let day = (date.getDate() + 1).toString().padStart(2, '0');
    let month = (date.getMonth() + 1).toString().padStart(2, '0');
    let year = date.getFullYear();

    return `${day}/${month}/${year}`;
}

function onEntryClick()
{
    console.log(this.getElementsByClassName("client-name-cell")[0].textContent)
}

function clearTable()
{
    console.log("limmpar tabela nao implementado");
}

function loadClientsTable()
{
    clearTable();

    for(let client of clients)
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
        currencyCodeCell.textContent = currencies.find((currency) => currency.code === client.currencyCode).abbreviation;
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
        totalSalesCell.textContent = client.totalSales.toFixed(currencies.find((currency) => currency.code === client.currencyCode).decimalPlaces);
        totalSalesCell.classList.add("total-sales-cell");
        row.appendChild(totalSalesCell);

        row.onclick = onEntryClick;

        clientsTableBody.appendChild(row);
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
        console.error("Erro no fetch placeholder", error);
    }
}

async function intializePage()
{
    await getClients();
    await getCurrencies();
    loadClientsTable();
}

intializePage();
