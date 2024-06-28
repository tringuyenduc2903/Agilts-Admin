crud.field("identifications")
    .subfield("default")
    .onChange((field) => {
        if (field.value !== "1") return;

        const table = $(
            "[data-repeatable-holder='identifications']",
        ).children();

        $.each(table, (row) => {
            row++;

            if (row !== field.rowNumber)
                crud.field("identifications")
                    .subfield("default", row)
                    .uncheck(true);
        });
    });
