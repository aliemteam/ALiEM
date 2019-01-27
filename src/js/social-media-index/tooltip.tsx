import * as React from 'react';

export default class Tip extends React.PureComponent<any, any> {
    render() {
        const { isActive, text } = this.props;
        return isActive ? <strong children={text} /> : null;
    }
}
