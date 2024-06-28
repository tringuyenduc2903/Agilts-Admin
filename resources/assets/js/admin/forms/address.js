crud.field("addresses")
    .subfield("default")
    .onChange((field) => {
        if (field.value !== "1") return;

        const table = $("[data-repeatable-holder='addresses']").children();

        $.each(table, (row) => {
            if (row + 1 !== field.rowNumber)
                crud.field("addresses")
                    .subfield("default", row + 1)
                    .uncheck(true);
        });
    });
