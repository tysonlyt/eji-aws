function FilterObjects(filterDetails) {

    if (!filterDetails || !filterDetails.protector || !filterDetails.event) {

        return false;
    }

    return {
        "css-class": new CustomCssClasses(filterDetails.protector, filterDetails.event),
    };
}
