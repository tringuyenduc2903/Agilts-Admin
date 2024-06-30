crud.field("options")
    .subfield("price")
    .onChange((field) => {
        let value = parseFloat(field.value);

        if (isNaN(value)) value = 0;

        crud
            .field("options")
            .subfield("price_preview", field.rowNumber).input.value =
            value.toLocaleString();
    })
    .change();
