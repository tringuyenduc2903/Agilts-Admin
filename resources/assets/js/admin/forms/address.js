const handleUncheck = (field) => {
    const table = $("[data-repeatable-holder='addresses']").children();

    $.each(table, (row) => {
        row++;

        if (row !== field.rowNumber)
            crud.field("addresses").subfield("default", row).uncheck(true);
    });
};

crud.field("addresses")
    .subfield("default")
    .onChange((field) => {
        if (field.value === "1") handleUncheck(field);
    });
