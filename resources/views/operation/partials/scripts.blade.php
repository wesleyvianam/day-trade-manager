<script>

const valueDollar = document.querySelector('#dollar-futuro');

valueDollar.addEventListener('input', () => {
    valueDollar.value = (valueDollar.value);
    const rawValue = valueDollar.value.replace(/\D/g, "");
    const formattedValue = formatDollar(rawValue);
    valueDollar.value = formattedValue;
    saveDollar(formattedValue);

    setDollar(valueDollar.value)
})

function setDollar(dollar) {
    dollar = dollar.replace(',', '.');

    document.querySelector('#dollarNow').textContent = new Intl.NumberFormat('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(dollar * 1000);

    const input = document.querySelector('#purchase_value');
    input.value = new Intl.NumberFormat('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(dollar * 1000);

    const inputFinish = document.querySelector('#finish_value');
    inputFinish.value = new Intl.NumberFormat('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(dollar * 1000);

    const inputDollarFinish = document.querySelector('#finish_dollar_value');
    inputDollarFinish.value = dollar;

    const purchaseDollar = document.querySelector('#purchase_dollar_value');
    purchaseDollar.value = dollar;
}


function formatCurrency(value) {
    value = value.replace(/\D/g, "");
    value = (value / 100).toFixed(2) + "";
    value = value.replace(".", ",");
    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    return value;
}

function formatDollar(value) {
    value = value.replace(/\D/g, "");
    if (value === "") value = "0";
    value = (parseInt(value) / 1000).toFixed(3);
    value = value.replace(".", ",");
    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

    return value;
}

function generateCode() {
    const now = new Date();
    const month = now.getMonth() + 1;
    const day = now.getDate();

    const year = (month === 11 && day === 30) || month === 12
        ? String(now.getFullYear() + 1).slice(-2)
        : String(now.getFullYear()).slice(-2);

    const monthCodes = {
        1: { letter: 'G', lastDay: 30 },
        2: { letter: 'H', lastDay: 27 },
        3: { letter: 'J', lastDay: 30 },
        4: { letter: 'K', lastDay: 29 },
        5: { letter: 'M', lastDay: 30 },
        6: { letter: 'N', lastDay: 29 },
        7: { letter: 'Q', lastDay: 30 },
        8: { letter: 'U', lastDay: 30 },
        9: { letter: 'V', lastDay: 29 },
        10: { letter: 'X', lastDay: 30 },
        11: { letter: 'Z', lastDay: 29 },
        12: { letter: 'F', lastDay: 30 }
    };

    const { letter, lastDay } = monthCodes[month];
    const monthLetter = day <= lastDay ? letter : monthCodes[month + 1]?.letter || 'F';

    return `WDO${monthLetter}${year}`;
}

function formatMonetary(number) {
    return (number / 100).toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
}

function getDollar() {
    const dollar = localStorage.getItem('dollar');
    if (dollar) {
        const valueDollar = document.querySelector('#dollar-futuro');
        valueDollar.value = dollar;

        setTimeout(() => {
            setDollar(dollar);
        }, 500)
    }
}

function saveDollar(value) {
    console.log('here');
    console.log(value);

    localStorage.setItem('dollar', value)
}

getDollar();

</script>
