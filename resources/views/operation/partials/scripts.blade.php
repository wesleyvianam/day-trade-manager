<script>

function formatCurrency(value) {
    value = value.replace(/\D/g, "");
    value = (value / 100).toFixed(2) + "";
    value = value.replace(".", ",");
    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    return value;
}

function generateCode() {
    const now = new Date();
    const year = String(now.getFullYear()).slice(-2);
    const month = now.getMonth() + 1;
    const day = now.getDate();

   
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

</script>