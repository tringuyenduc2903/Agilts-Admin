const handleUncheck = (field) => {
    const currentType = crud
        .field("addresses")
        .subfield("type", field.rowNumber).value;

    const table = $("[data-repeatable-holder='addresses']").children();

    $.each(table, (row) => {
        row++;

        const type = crud.field("addresses").subfield("type", row).value;

        if (type === currentType && row !== field.rowNumber)
            crud.field("addresses").subfield("default", row).uncheck(true);
    });
};

crud.field("addresses")
    .subfield("default")
    .onChange((field) => {
        if (field.value === "1") handleUncheck(field);
    });

crud.field("addresses")
    .subfield("type")
    .onChange((field) => {
        const value = crud
            .field("addresses")
            .subfield("default", field.rowNumber).value;

        if (value === "1") handleUncheck(field);
    });
