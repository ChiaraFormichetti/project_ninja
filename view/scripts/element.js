
const createHtml = (elements) => {
    if (elements.length > 0) {
        elements.forEach(
            element => {
                if (element.parentElement || element.parentId) {
                    const elementNode = document.createElement(element.tagName);
                    if (elementNode) {
                        if (element.events && element.events.length > 0) {
                            element.events.forEach(
                                eventObj => {
                                    const fn = eventObj.callbackName;
                                    elementNode.addEventListener(eventObj.eventName, () => fn(...eventObj.parameters))
                                });
                        }
                        if (element.content) {
                            elementNode.textContent = element.content;
                        }
                        if (element.id) {
                            elementNode.id = element.id;
                        }
                        if (element.attributes && element.attributes.length > 0) {
                            for ([attributes, values] of Object.entries(element.attributes)) {
                                elementNode.attributes = values;
                            }
                        }
                        let parentElement;
                        if (element.parentId) {
                            parentElement = document.getElementById(element.parentId);
                        } else if (element.parentElement) {

                            parentElement = element.parentElement;
                        }
                        parentElement.appendChild(elementNode);
                    }
                }
            }
        )
    }
}
export default createHtml;