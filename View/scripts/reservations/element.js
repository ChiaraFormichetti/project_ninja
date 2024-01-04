
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
                        if (element.attributes && Object.keys(element.attributes).length > 0) {
                            for(const [attribute, value] of Object.entries(element.attributes)) {
                                elementNode.setAttribute(attribute,value);
                            }
                        }
                        let parentElement;
                        if (element.parentId) {
                            parentElement = document.getElementById(element.parentId);
                        } else if (element.parentElement) {

                            parentElement = element.parentElement;
                        }
                        if (element.children && element.children.length > 0) {
                            createHtml(element.children.map(
                                child => {
                                    child.parentElement = elementNode;
                                    return child 
                                }
                            ));
                        }
                        parentElement.appendChild(elementNode);
                    }
                }
            }
        )
    }
}
export default createHtml;
