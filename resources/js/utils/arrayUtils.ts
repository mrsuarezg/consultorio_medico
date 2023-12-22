interface Item {
    [key: string]: any;
}

function getItemByField(
    field: string,
    value: string | number,
    itemList: Item[]
) {
    return itemList.find((item) => item[field] === value);
}

export function getItemById(id: number, itemList: Item[]) {
    return getItemByField("id", id, itemList);
}

/*
 * Get item name by id from a list of items
 * @param id: number
 * @param itemList: Item[]
 * @return string
 * @example getItemNameById(1, [{id: 1, name: "Item 1"}, {id: 2, name: "Item 2"}]) => "Item 1"
 */

export function getItemNameById(id: number, itemList: Item[]) {
    const item = getItemById(id, itemList);
    return item ? item.name : "Desconocido";
}
